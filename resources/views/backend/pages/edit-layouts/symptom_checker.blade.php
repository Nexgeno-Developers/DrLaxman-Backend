@php
    $getMeta = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
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
    $breadcrumb_image = $getMeta('breadcrumb_image');
    $breadcrumb_key_highlights = $normalizeTagValues($getMeta('breadcrumb_key_highlights'));
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
        <label class="form-label">Key Highlights <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[breadcrumb_key_highlights]" value="{{ $breadcrumb_key_highlights }}" placeholder="Enter key highlights separated by commas" required>
    </div>
</div>
