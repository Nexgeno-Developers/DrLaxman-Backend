@php
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

    $breadcrumb_subtitle = $getMeta('breadcrumb_subtitle');
    $breadcrumb_title = $getMeta('breadcrumb_title');
    $breadcrumb_description = $getMeta('breadcrumb_description');
    $breadcrumb_key_highlights = $normalizeTagValues($getMeta('breadcrumb_key_highlights'));
    $breadcrumb_image = $getMeta('breadcrumb_image');
    $breadcrumb_image_overlay_title = $getMeta('breadcrumb_image_overlay_title');
    $breadcrumb_image_overlay_description = $getMeta('breadcrumb_image_overlay_description');

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

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Key Highlights <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[breadcrumb_key_highlights]" value="{{ $breadcrumb_key_highlights }}" placeholder="Enter key highlights separated by commas" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Image <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $breadcrumb_image }}" type="hidden" name="meta[breadcrumb_image]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Image Overlay Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_image_overlay_title]" value="{{ $breadcrumb_image_overlay_title }}" placeholder="Enter image overlay title" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Image Overlay Description <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_image_overlay_description]" value="{{ $breadcrumb_image_overlay_description }}" placeholder="Enter image overlay description" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">FAQs Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[faq_subtitle]" value="{{ $faq_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[faq_title]" value="{{ $faq_title }}" placeholder="Enter title" required>
    </div>

    <div class="faq-target col-md-12">
        @if(isset($faq_items['itration']) && is_array($faq_items['itration']))
            @foreach($faq_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[faq_items][itration][]" type="hidden" required>
                            </div>

                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Question <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[faq_items][question][]" value="{{ $faq_items['question'][$index] ?? '' }}" placeholder="Enter question" required>
                            </div>

                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Answer <span class="text-danger">*</span></label>
                                <textarea name="meta[faq_items][answer][]" class="form-control text-editor" rows="3" required>{{ $faq_items['answer'][$index] ?? '' }}</textarea>
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
        <button
            type="button"
            class="mt-1 btn btn-soft-success btn-icon w-100 mb-2"
            data-toggle="add-more"
            data-limit="20"
            data-content='
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="data" name="meta[faq_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Question <span class="text-danger">*</span></label>
                                <input type="text" name="meta[faq_items][question][]" class="form-control" placeholder="Enter question" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Answer <span class="text-danger">*</span></label>
                                <textarea name="meta[faq_items][answer][]" class="form-control text-editor" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 btn-dynamic-fields">
                        <button type="button" class="btn btn-icon btn-circle btn-soft-danger mb-1" data-toggle="remove-parent" data-parent=".remove-parent">
                            <i class="ti ti-x"></i>
                        </button>
                    </div>
                </div>
            '
            data-target=".faq-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>
