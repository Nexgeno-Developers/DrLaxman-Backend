<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\FormSubmissionMail;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FormSubmissionController extends Controller
{
    /**
     * Submit a form via API.
     *
     * Expected:
     * - `form_name`
     * - `name`, `email`, `phone` (depending on form_name rules)
     * - Any other validated scalar fields as per `getValidationRules()`
     * - Uploaded files as top-level multipart form-data fields (e.g. `image`, `resume`, `pdf`, etc.)
     *
     * Uploaded file paths will be stored inside `forms.form_data` under the same field name.
     */
    public function submit(Request $request)
    {
        if ($request->filled('referer_url') && !$request->filled('referrer_url')) {
            $request->merge([
                'referrer_url' => $request->input('referer_url'),
            ]);
        }

        $normalizedFiles = $this->normalizedFiles($request);
        $this->logUploadDebug('normalized_files', [
            'form_name' => $request->input('form_name'),
            'all_input_keys' => array_keys($request->all()),
            'all_file_keys' => array_keys($request->allFiles()),
            'normalized_file_keys' => array_keys($normalizedFiles),
            'all_files' => $this->describeFiles($request->allFiles()),
            'normalized_files' => $this->describeFiles($normalizedFiles),
        ]);

        $formName = $request->input('form_name');
        if (!$formName) {
            return response()->json([
                'error' => [
                    'message' => 'form_name is required',
                    'code' => 'FORM_NAME_REQUIRED',
                ],
            ], 422);
        }

        $validationRules = $this->getValidationRules($formName);
        $validationData = array_merge($request->all(), $normalizedFiles);
        if (isset($validationData['reports']) && $validationData['reports'] instanceof UploadedFile) {
            $validationData['reports'] = [$validationData['reports']];
        }
        $validatedData = Validator::make($validationData, $validationRules)->validate();

        $companyId = $request->input('company_id') ?? 1;

        $formData = collect($validatedData)
            ->except(['form_name', 'name', 'email', 'phone', 'company_id', 'reports'])
            ->toArray();

        foreach ($normalizedFiles as $field => $fileValue) {
            $stored = $this->storeFileValue($fileValue, $formName, (string) $companyId);
            if ($stored === null) {
                $this->logUploadDebug('store_skipped', [
                    'field' => $field,
                    'reason' => 'storeFileValue returned null',
                    'incoming' => $this->describeSingleFileValue($fileValue),
                ]);
                continue;
            }

            $formData[$field] = $stored;
            $this->logUploadDebug('stored_file', [
                'field' => $field,
                'stored' => $stored,
            ]);
        }

        $name = $request->input('name');

        if (empty($name)) {
            $first = $request->input('first_name') ?? '';
            $last  = $request->input('last_name') ?? '';
            $name = trim($first . ' ' . $last);
        }        

        $form = Form::create([
            'form_name' => $formName,
            'name' => $name,
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'form_data' => $formData,
            'ip' => $request->ip(),
            'company_id' => $companyId,
        ]);

        $recipientEmail = config('custom.from_email');
        if (!empty($recipientEmail)) {
            try {
                Mail::to([$recipientEmail])->send(
                    new FormSubmissionMail($formName, $this->buildMailData($validatedData, $formData))
                );
            } catch (\Throwable $e) {
                logger('Form submission mail failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'data' => [
                'id' => $form->id,
                'form_name' => $form->form_name,
                'created_at' => $form->created_at,
                'form_data' => $form->form_data,
            ],
        ], 201);
    }

    private function normalizedFiles(Request $request): array
    {
        $files = $request->allFiles();

        if (isset($files['reports[]']) && !isset($files['reports'])) {
            $files['reports'] = $files['reports[]'];
            unset($files['reports[]']);
        }

        return $files;
    }

    private function describeFiles(array $files): array
    {
        $described = [];

        foreach ($files as $key => $value) {
            $described[$key] = $this->describeSingleFileValue($value);
        }

        return $described;
    }

    private function describeSingleFileValue(mixed $value): array|string|null
    {
        if ($value instanceof UploadedFile) {
            return [
                'kind' => 'single',
                'original_name' => $value->getClientOriginalName(),
                'mime' => $value->getMimeType(),
                'size' => $value->getSize(),
            ];
        }

        if (is_array($value)) {
            return array_map(function ($item) {
                if ($item instanceof UploadedFile) {
                    return [
                        'kind' => 'item',
                        'original_name' => $item->getClientOriginalName(),
                        'mime' => $item->getMimeType(),
                        'size' => $item->getSize(),
                    ];
                }

                return gettype($item);
            }, $value);
        }

        return is_object($value) ? get_class($value) : (is_scalar($value) ? (string) $value : gettype($value));
    }

    private function logUploadDebug(string $stage, array $context): void
    {
        Log::debug('FormSubmission upload debug: ' . $stage, $context);
    }

    /**
     * Store either:
     * - a single UploadedFile
     * - an array of UploadedFile
     *
     * Returns:
     * - string path for a single file
     * - string[] for multiple files
     * - null if the provided value isn't a valid UploadedFile (ignored)
     */
    private function storeFileValue(mixed $fileValue, string $formName, string $companyId): array|string|null
    {
        if ($fileValue instanceof UploadedFile) {
            return $this->storeOneFile($fileValue, $formName, $companyId);
        }

        if (is_array($fileValue)) {
            $paths = [];
            foreach ($fileValue as $maybeFile) {
                if ($maybeFile instanceof UploadedFile) {
                    $paths[] = $this->storeOneFile($maybeFile, $formName, $companyId);
                }
            }

            if (count($paths) === 0) {
                return null;
            }

            return count($paths) === 1 ? $paths[0] : $paths;
        }

        return null;
    }

    private function storeOneFile(UploadedFile $file, string $formName, string $companyId): string
    {
        $allowedMimeMap = [
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        ];

        $mimeType = strtolower((string) $file->getMimeType());
        $extension = $allowedMimeMap[$mimeType] ?? null;

        if ($extension === null) {
            abort(422, 'Disallowed file type');
        }

        $maxSizeBytes = 10 * 1024 * 1024; // 10MB (keeps things reasonable for forms)
        if ($file->getSize() > $maxSizeBytes) {
            abort(422, 'File too large');
        }

        $date = date('Y/m');

        // Store on the `public` disk and return the storage-relative public path.
        $path = $file->storeAs(
            'uploads/forms/' . $formName . '/' . $companyId . '/' . $date,
            Str::random(20) . '.' . $extension,
            'public'
        );

        // The stored value is designed to be compatible with `my_asset($value)`.
        return 'storage/' . $path;
    }

    private function buildMailData(array $validatedData, array $formData): array
    {
        $mailData = array_merge(
            collect($validatedData)
                ->except(['reports'])
                ->toArray(),
            $formData
        );

        array_walk_recursive($mailData, function (&$value) {
            if (!is_string($value)) {
                return;
            }

            if (str_starts_with($value, 'storage/')) {
                $value = url($value);
            }
        });

        return $mailData;
    }

    private function getValidationRules(string $formName): array
    {
        switch ($formName) {
            case 'general_consultation':
                return [
                    'form_name' => 'required|string|in:general_consultation',
                    'name' => 'required|string|max:100',
                    'email' => 'required|email|max:100',
                    'phone' => 'required|string|max:30',
                    'centre' => 'required|string|max:255',
                    'symptoms' => 'required|string|max:1000',
                    'page_url' => 'required|url|max:2048',
                    'referrer_url' => 'nullable|url|max:2048',
                ];

            case 'international_video_call':
                return [
                    'form_name' => 'required|string|in:international_video_call',
                    'name' => 'required|string|max:100',
                    'email' => 'required|email|max:100',
                    'phone' => 'required|string|max:30',
                    'country' => 'required|string|max:100',
                    'timezone' => 'required|string|max:100',
                    'datetime' => 'required|string|max:100',
                    'condition' => 'required|string|max:255',
                    'reports' => 'nullable|array',
                    'reports.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
                    'page_url' => 'required|url|max:2048',
                    'referrer_url' => 'nullable|url|max:2048',
                ];

            case 'home_blood_test':
                return [
                    'form_name' => 'required|string|in:home_blood_test',
                    'name' => 'required|string|max:100',
                    'phone' => 'required|string|max:30',
                    'home_address' => 'required|string|max:1000',
                    'date' => 'required|string|max:50',
                    'time_slot' => 'required|string|max:100',
                    'test' => 'required|string|max:255',
                    'page_url' => 'required|url|max:2048',
                    'referrer_url' => 'nullable|url|max:2048',
                ];

            default:
                return [
                    'form_name' => 'required|max:20',
                ];
        }
    }
}
