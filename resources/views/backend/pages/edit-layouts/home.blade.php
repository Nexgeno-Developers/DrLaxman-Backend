@php
    use App\Models\Page;

    $getMeta = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $getRepeater = function ($key) use ($getMeta) {
        $data = json_decode($getMeta($key, '[]'), true);

        return is_array($data) ? $data : [];
    };

    $normalizeTagValues = function ($value) {
        $decoded = is_string($value) ? json_decode($value, true) : $value;

        if (is_array($decoded)) {
            if (isset($decoded['value']) && is_array($decoded['value'])) {
                return collect($decoded['value'])
                    ->map(function ($item) {
                        if (is_array($item)) {
                            return trim((string) ($item['value'] ?? $item['description'] ?? $item['title'] ?? ''));
                        }

                        return trim((string) $item);
                    })
                    ->filter()
                    ->implode(',');
            }

            if (array_is_list($decoded)) {
                return collect($decoded)
                    ->map(function ($item) {
                        if (is_array($item)) {
                            return trim((string) ($item['value'] ?? $item['description'] ?? $item['title'] ?? ''));
                        }

                        return trim((string) $item);
                    })
                    ->filter()
                    ->implode(',');
            }
        }

        return (string) $value;
    };

    $breadcrumb_strip_text = $getMeta('breadcrumb_strip_text');
    $breadcrumb_subtitle = $getMeta('breadcrumb_subtitle');
    $breadcrumb_title = $getMeta('breadcrumb_title');
    $breadcrumb_description = $getMeta('breadcrumb_description');
    $breadcrumb_items = $getRepeater('breadcrumb_items');

    $about_subtitle = $getMeta('about_subtitle');
    $about_title = $getMeta('about_title');
    $about_tagline = $getMeta('about_tagline');
    $about_education = $normalizeTagValues($getMeta('about_education'));
    $about_overview = $getMeta('about_overview');
    $about_picture = $getMeta('about_picture');
    $about_picture_overlay_title = $getMeta('about_picture_overlay_title');
    $about_picture_overlay_description = $getMeta('about_picture_overlay_description');
    $about_items = $getRepeater('about_items');

    $conditions_subtitle = $getMeta('conditions_subtitle');
    $conditions_title = $getMeta('conditions_title');
    $selectedConditions = json_decode($getMeta('conditions', '[]'), true);
    $selectedConditions = is_array($selectedConditions) ? array_map('intval', $selectedConditions) : [];
    $conditionOptions = Page::query()
        ->whereIn('layout', ['conditions', 'condition_details'])
        ->where('is_active', true)
        ->orderBy('title')
        ->get(['id', 'title']);

    $achievements_subtitle = $getMeta('achievements_subtitle');
    $achievements_title = $getMeta('achievements_title');
    $achievement_items = $getRepeater('achievement_items');

    $symptom_checker_subtitle = $getMeta('symptom_checker_subtitle');
    $symptom_checker_title = $getMeta('symptom_checker_title');
    $symptom_checker_description = $getMeta('symptom_checker_description');
    $symptom_checker_items = $getRepeater('symptom_checker_items');

    $blood_test_subtitle = $getMeta('blood_test_subtitle');
    $blood_test_title = $getMeta('blood_test_title');
    $blood_test_description = $getMeta('blood_test_description');

    $why_we_subtitle = $getMeta('why_we_subtitle');
    $why_we_title = $getMeta('why_we_title');
    $why_we_description = $getMeta('why_we_description');
    $why_we_image = $getMeta('why_we_image');
    $why_we_image_overlay_subtitle = $getMeta('why_we_image_overlay_subtitle');
    $why_we_image_overlay_title = $getMeta('why_we_image_overlay_title');
    $why_we_image_overlay_description = $getMeta('why_we_image_overlay_description');
    $why_we_items = $getRepeater('why_we_items');

    $why_choose_subtitle = $getMeta('why_choose_subtitle');
    $why_choose_title = $getMeta('why_choose_title');
    $why_choose_description = $getMeta('why_choose_description');
    $why_choose_comparison_items = $getRepeater('why_choose_comparison_items');

    $plan_your_surgery_subtitle = $getMeta('plan_your_surgery_subtitle');
    $plan_your_surgery_title = $getMeta('plan_your_surgery_title');
    $plan_your_surgery_description = $getMeta('plan_your_surgery_description');
    $plan_your_surgery_items = $getRepeater('plan_your_surgery_items');

    $reviews_subtitle = $getMeta('reviews_subtitle');
    $reviews_title = $getMeta('reviews_title');

    $centre_subtitle = $getMeta('centre_subtitle');
    $centre_title = $getMeta('centre_title');

    $blogs_subtitle = $getMeta('blogs_subtitle');
    $blogs_title = $getMeta('blogs_title');

    $faq_subtitle = $getMeta('faq_subtitle');
    $faq_title = $getMeta('faq_title');
    $faq_items = $getRepeater('faq_items');
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Strip Text <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_strip_text]" value="{{ $breadcrumb_strip_text }}" placeholder="Enter strip text" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_subtitle]" value="{{ $breadcrumb_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_title]" value="{{ $breadcrumb_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[breadcrumb_description]" class="form-control text-editor" rows="4" required>{{ $breadcrumb_description }}</textarea>
    </div>

    <div class="breadcrumb-items-target col-md-12">
        @if(isset($breadcrumb_items['itration']) && is_array($breadcrumb_items['itration']))
            @foreach($breadcrumb_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[breadcrumb_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[breadcrumb_items][icon][]" class="selected-files" value="{{ $breadcrumb_items['icon'][$index] ?? '' }}" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[breadcrumb_items][title][]" value="{{ $breadcrumb_items['title'][$index] ?? '' }}" placeholder="Enter title" required>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="meta[breadcrumb_items][description][]" class="form-control" rows="3" placeholder="Enter description" required>{{ $breadcrumb_items['description'][$index] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 btn-dynamic-fields">
                        <button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="col-md-12">
        <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="12" data-content='
            <div class="row remove-parent">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-12">
                            <input value="data" name="meta[breadcrumb_items][itration][]" type="hidden" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Icon <span class="text-danger">*</span></label>
                            <div class="form-group mb-2">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                    <input type="hidden" name="meta[breadcrumb_items][icon][]" class="selected-files" required>
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group mb-2">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="meta[breadcrumb_items][title][]" class="form-control" placeholder="Enter title" required>
                        </div>
                        <div class="col-md-4 form-group mb-2">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea name="meta[breadcrumb_items][description][]" class="form-control" rows="3" placeholder="Enter description" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 btn-dynamic-fields">
                    <button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
        ' data-target=".breadcrumb-items-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">About Section</h4>
    </div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[about_subtitle]" value="{{ $about_subtitle }}" placeholder="Enter subtitle" required>
    </div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[about_title]" value="{{ $about_title }}" placeholder="Enter title" required>
    </div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Tagline <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[about_tagline]" value="{{ $about_tagline }}" placeholder="Enter tagline" required>
    </div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Education <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[about_education]" value="{{ $about_education }}" placeholder="Enter education separated by commas" required>
    </div>
    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Overview <span class="text-danger">*</span></label>
        <textarea name="meta[about_overview]" class="form-control text-editor" rows="4" required>{{ $about_overview }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Picture <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $about_picture }}" type="hidden" name="meta[about_picture]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Picture Overlay Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[about_picture_overlay_title]" value="{{ $about_picture_overlay_title }}" placeholder="Enter picture overlay title" required>
    </div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Picture Overlay Description <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[about_picture_overlay_description]" value="{{ $about_picture_overlay_description }}" placeholder="Enter picture overlay description" required>
    </div>
    <div class="about-items-target col-md-12">
        @if(isset($about_items['itration']) && is_array($about_items['itration']))
            @foreach($about_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12"><input value="{{ $index }}" name="meta[about_items][itration][]" type="hidden" required></div>
                            <div class="col-md-6">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div></div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[about_items][icon][]" class="selected-files" value="{{ $about_items['icon'][$index] ?? '' }}" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[about_items][title][]" value="{{ $about_items['title'][$index] ?? '' }}" placeholder="Enter title" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-md-12">
        <button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="12" data-content='
            <div class="row remove-parent">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-12"><input value="data" name="meta[about_items][itration][]" type="hidden" required></div>
                        <div class="col-md-6">
                            <label class="form-label">Icon <span class="text-danger">*</span></label>
                            <div class="form-group mb-2">
                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                    <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div></div>
                                    <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                    <input type="hidden" name="meta[about_items][icon][]" class="selected-files" required>
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        </div>
                        <div class="col-md-6 form-group mb-2">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="meta[about_items][title][]" class="form-control" placeholder="Enter title" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div>
            </div>
        ' data-target=".about-items-target"><i class="ti ti-plus"></i><span class="ml-2">Add More</span></button>
    </div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Conditions Section</h4></div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[conditions_subtitle]" value="{{ $conditions_subtitle }}" placeholder="Enter subtitle" required>
    </div>
    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[conditions_title]" value="{{ $conditions_title }}" placeholder="Enter title" required>
    </div>
    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Conditions <span class="text-danger">*</span></label>
        <select class="form-control select2" name="meta[conditions][]" multiple required>
            @foreach($conditionOptions as $condition)
                <option value="{{ $condition->id }}" {{ in_array((int) $condition->id, $selectedConditions, true) ? 'selected' : '' }}>{{ $condition->title }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Achievements Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[achievements_subtitle]" value="{{ $achievements_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[achievements_title]" value="{{ $achievements_title }}" placeholder="Enter title" required></div>
    <div class="achievement-items-target col-md-12">
        @if(isset($achievement_items['itration']) && is_array($achievement_items['itration']))
            @foreach($achievement_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11"><div class="row">
                        <div class="col-md-12"><input value="{{ $index }}" name="meta[achievement_items][itration][]" type="hidden" required></div>
                        <div class="col-md-3"><label class="form-label">Icon <span class="text-danger">*</span></label><div class="form-group mb-2"><div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false"><div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div></div><div class="form-control file-amount">{{ __('Choose File') }}</div><input type="hidden" name="meta[achievement_items][icon][]" class="selected-files" value="{{ $achievement_items['icon'][$index] ?? '' }}" required></div><div class="file-preview box sm"></div></div></div>
                        <div class="col-md-3 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[achievement_items][subtitle][]" value="{{ $achievement_items['subtitle'][$index] ?? '' }}" placeholder="Enter subtitle" required></div>
                        <div class="col-md-3 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[achievement_items][title][]" value="{{ $achievement_items['title'][$index] ?? '' }}" placeholder="Enter title" required></div>
                        <div class="col-md-3 form-group mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[achievement_items][description][]" value="{{ $achievement_items['description'][$index] ?? '' }}" placeholder="Enter description" required></div>
                    </div></div>
                    <div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-md-12"><button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="12" data-content='
        <div class="row remove-parent">
            <div class="col-md-11"><div class="row">
                <div class="col-md-12"><input value="data" name="meta[achievement_items][itration][]" type="hidden" required></div>
                <div class="col-md-3"><label class="form-label">Icon <span class="text-danger">*</span></label><div class="form-group mb-2"><div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false"><div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div></div><div class="form-control file-amount">{{ __('Choose File') }}</div><input type="hidden" name="meta[achievement_items][icon][]" class="selected-files" required></div><div class="file-preview box sm"></div></div></div>
                <div class="col-md-3 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" name="meta[achievement_items][subtitle][]" class="form-control" placeholder="Enter subtitle" required></div>
                <div class="col-md-3 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" name="meta[achievement_items][title][]" class="form-control" placeholder="Enter title" required></div>
                <div class="col-md-3 form-group mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><input type="text" name="meta[achievement_items][description][]" class="form-control" placeholder="Enter description" required></div>
            </div></div>
            <div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div>
        </div>
    ' data-target=".achievement-items-target"><i class="ti ti-plus"></i><span class="ml-2">Add More</span></button></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Symptom Checker Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[symptom_checker_subtitle]" value="{{ $symptom_checker_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[symptom_checker_title]" value="{{ $symptom_checker_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[symptom_checker_description]" value="{{ $symptom_checker_description }}" placeholder="Enter description" required></div>
    <div class="symptom-checker-items-target col-md-12">
        @if(isset($symptom_checker_items['itration']) && is_array($symptom_checker_items['itration']))
            @foreach($symptom_checker_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11"><div class="row">
                        <div class="col-md-12"><input value="{{ $index }}" name="meta[symptom_checker_items][itration][]" type="hidden" required></div>
                        <div class="col-md-6"><label class="form-label">Icon <span class="text-danger">*</span></label><div class="form-group mb-2"><div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false"><div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div></div><div class="form-control file-amount">{{ __('Choose File') }}</div><input type="hidden" name="meta[symptom_checker_items][icon][]" class="selected-files" value="{{ $symptom_checker_items['icon'][$index] ?? '' }}" required></div><div class="file-preview box sm"></div></div></div>
                        <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[symptom_checker_items][title][]" value="{{ $symptom_checker_items['title'][$index] ?? '' }}" placeholder="Enter title" required></div>
                    </div></div>
                    <div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="col-md-12"><button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="12" data-content='
        <div class="row remove-parent">
            <div class="col-md-11"><div class="row">
                <div class="col-md-12"><input value="data" name="meta[symptom_checker_items][itration][]" type="hidden" required></div>
                <div class="col-md-6"><label class="form-label">Icon <span class="text-danger">*</span></label><div class="form-group mb-2"><div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false"><div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div></div><div class="form-control file-amount">{{ __('Choose File') }}</div><input type="hidden" name="meta[symptom_checker_items][icon][]" class="selected-files" required></div><div class="file-preview box sm"></div></div></div>
                <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" name="meta[symptom_checker_items][title][]" class="form-control" placeholder="Enter title" required></div>
            </div></div>
            <div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div>
        </div>
    ' data-target=".symptom-checker-items-target"><i class="ti ti-plus"></i><span class="ml-2">Add More</span></button></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Blood Test Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[blood_test_subtitle]" value="{{ $blood_test_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[blood_test_title]" value="{{ $blood_test_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[blood_test_description]" value="{{ $blood_test_description }}" placeholder="Enter description" required></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Why We Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_we_subtitle]" value="{{ $why_we_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_we_title]" value="{{ $why_we_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_we_description]" value="{{ $why_we_description }}" placeholder="Enter description" required></div>
    <div class="col-md-6"><label class="form-label">Image <span class="text-danger">*</span></label><div class="form-group mb-2"><div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false"><div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div></div><div class="form-control file-amount">{{ __('Choose File') }}</div><input value="{{ $why_we_image }}" type="hidden" name="meta[why_we_image]" class="selected-files" required></div><div class="file-preview box sm"></div></div></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Image Overlay Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_we_image_overlay_subtitle]" value="{{ $why_we_image_overlay_subtitle }}" placeholder="Enter image overlay subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Image Overlay Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_we_image_overlay_title]" value="{{ $why_we_image_overlay_title }}" placeholder="Enter image overlay title" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Image Overlay Description <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_we_image_overlay_description]" value="{{ $why_we_image_overlay_description }}" placeholder="Enter image overlay description" required></div>
    <div class="why-we-items-target col-md-12">
        @if(isset($why_we_items['itration']) && is_array($why_we_items['itration']))
            @foreach($why_we_items['itration'] as $index => $itration)
                <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="{{ $index }}" name="meta[why_we_items][itration][]" type="hidden" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_we_items][title][]" value="{{ $why_we_items['title'][$index] ?? '' }}" placeholder="Enter title" required></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
            @endforeach
        @endif
    </div>
    <div class="col-md-12"><button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="12" data-content='
        <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="data" name="meta[why_we_items][itration][]" type="hidden" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" name="meta[why_we_items][title][]" class="form-control" placeholder="Enter title" required></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
    ' data-target=".why-we-items-target"><i class="ti ti-plus"></i><span class="ml-2">Add More</span></button></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Why Choose Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_choose_subtitle]" value="{{ $why_choose_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_choose_title]" value="{{ $why_choose_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_choose_description]" value="{{ $why_choose_description }}" placeholder="Enter description" required></div>
    <div class="why-choose-comparison-items-target col-md-12">
        @if(isset($why_choose_comparison_items['itration']) && is_array($why_choose_comparison_items['itration']))
            @foreach($why_choose_comparison_items['itration'] as $index => $itration)
                <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="{{ $index }}" name="meta[why_choose_comparison_items][itration][]" type="hidden" required></div><div class="col-md-4 form-group mb-2"><label class="form-label">Parameter <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_choose_comparison_items][parameter][]" value="{{ $why_choose_comparison_items['parameter'][$index] ?? '' }}" placeholder="Enter parameter" required></div><div class="col-md-4 form-group mb-2"><label class="form-label">Laser Surgery <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_choose_comparison_items][laser_surgery][]" value="{{ $why_choose_comparison_items['laser_surgery'][$index] ?? '' }}" placeholder="Enter laser surgery value" required></div><div class="col-md-4 form-group mb-2"><label class="form-label">Traditional Surgery <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[why_choose_comparison_items][traditional_surgery][]" value="{{ $why_choose_comparison_items['traditional_surgery'][$index] ?? '' }}" placeholder="Enter traditional surgery value" required></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
            @endforeach
        @endif
    </div>
    <div class="col-md-12"><button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="20" data-content='
        <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="data" name="meta[why_choose_comparison_items][itration][]" type="hidden" required></div><div class="col-md-4 form-group mb-2"><label class="form-label">Parameter <span class="text-danger">*</span></label><input type="text" name="meta[why_choose_comparison_items][parameter][]" class="form-control" placeholder="Enter parameter" required></div><div class="col-md-4 form-group mb-2"><label class="form-label">Laser Surgery <span class="text-danger">*</span></label><input type="text" name="meta[why_choose_comparison_items][laser_surgery][]" class="form-control" placeholder="Enter laser surgery value" required></div><div class="col-md-4 form-group mb-2"><label class="form-label">Traditional Surgery <span class="text-danger">*</span></label><input type="text" name="meta[why_choose_comparison_items][traditional_surgery][]" class="form-control" placeholder="Enter traditional surgery value" required></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
    ' data-target=".why-choose-comparison-items-target"><i class="ti ti-plus"></i><span class="ml-2">Add More</span></button></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Plan Your Surgery Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[plan_your_surgery_subtitle]" value="{{ $plan_your_surgery_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[plan_your_surgery_title]" value="{{ $plan_your_surgery_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label">Description <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[plan_your_surgery_description]" value="{{ $plan_your_surgery_description }}" placeholder="Enter description" required></div>
    <div class="plan-your-surgery-items-target col-md-12">
        @if(isset($plan_your_surgery_items['itration']) && is_array($plan_your_surgery_items['itration']))
            @foreach($plan_your_surgery_items['itration'] as $index => $itration)
                <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="{{ $index }}" name="meta[plan_your_surgery_items][itration][]" type="hidden" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[plan_your_surgery_items][title][]" value="{{ $plan_your_surgery_items['title'][$index] ?? '' }}" placeholder="Enter title" required></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
            @endforeach
        @endif
    </div>
    <div class="col-md-12"><button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="12" data-content='
        <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="data" name="meta[plan_your_surgery_items][itration][]" type="hidden" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" name="meta[plan_your_surgery_items][title][]" class="form-control" placeholder="Enter title" required></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
    ' data-target=".plan-your-surgery-items-target"><i class="ti ti-plus"></i><span class="ml-2">Add More</span></button></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Reviews Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[reviews_subtitle]" value="{{ $reviews_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[reviews_title]" value="{{ $reviews_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label d-block text-center">Reviews will be automatically fetched from Google My Business.</label></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Centre Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[centre_subtitle]" value="{{ $centre_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[centre_title]" value="{{ $centre_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label d-block text-center">Consulting centres will be automatically fetched from Centre Details.</label></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">Blogs Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[blogs_subtitle]" value="{{ $blogs_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[blogs_title]" value="{{ $blogs_title }}" placeholder="Enter title" required></div>
    <div class="col-md-12 form-group mb-2"><label class="form-label d-block text-center">Latest blogs will be automatically fetched.</label></div>
</div>

<div class="row">
    <div class="col-md-12"><hr><h4 class="text-primary">FAQs Section</h4></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Subtitle <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[faq_subtitle]" value="{{ $faq_subtitle }}" placeholder="Enter subtitle" required></div>
    <div class="col-md-6 form-group mb-2"><label class="form-label">Title <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[faq_title]" value="{{ $faq_title }}" placeholder="Enter title" required></div>
    <div class="faq-target col-md-12">
        @if(isset($faq_items['itration']) && is_array($faq_items['itration']))
            @foreach($faq_items['itration'] as $index => $itration)
                <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="{{ $index }}" name="meta[faq_items][itration][]" type="hidden" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Question <span class="text-danger">*</span></label><input type="text" class="form-control" name="meta[faq_items][question][]" value="{{ $faq_items['question'][$index] ?? '' }}" placeholder="Enter question" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Answer <span class="text-danger">*</span></label><textarea name="meta[faq_items][answer][]" class="form-control text-editor" rows="3" required>{{ $faq_items['answer'][$index] ?? '' }}</textarea></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
            @endforeach
        @endif
    </div>
    <div class="col-md-12"><button type="button" class="mt-1 btn btn-soft-success btn-icon w-100 mb-2" data-toggle="add-more" data-limit="20" data-content='
        <div class="row remove-parent"><div class="col-md-11"><div class="row"><div class="col-md-12"><input value="data" name="meta[faq_items][itration][]" type="hidden" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Question <span class="text-danger">*</span></label><input type="text" name="meta[faq_items][question][]" class="form-control" placeholder="Enter question" required></div><div class="col-md-12 form-group mb-2"><label class="form-label">Answer <span class="text-danger">*</span></label><textarea name="meta[faq_items][answer][]" class="form-control text-editor" rows="3" required></textarea></div></div></div><div class="col-md-1 btn-dynamic-fields"><button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent"><i class="ti ti-x"></i></button></div></div>
    ' data-target=".faq-target"><i class="ti ti-plus"></i><span class="ml-2">Add More</span></button></div>
</div>
