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
    $breadcrumb_image = $getMeta('breadcrumb_image');
    $breadcrumb_image_overlay_title = $getMeta('breadcrumb_image_overlay_title');
    $breadcrumb_image_overlay_description = $getMeta('breadcrumb_image_overlay_description');
    $breadcrumb_image_overlay_key_highlights = $normalizeTagValues($getMeta('breadcrumb_image_overlay_key_highlights'));

    $how_it_works_subtitle = $getMeta('how_it_works_subtitle');
    $how_it_works_title = $getMeta('how_it_works_title');
    $how_it_works_items = $getRepeater('how_it_works_items');

    $test_availability_subtitle = $getMeta('test_availability_subtitle');
    $test_availability_title = $getMeta('test_availability_title');
    $test_availability_key_highlights = $normalizeTagValues($getMeta('test_availability_key_highlights'));
    $test_availability_description = $getMeta('test_availability_description');

    $coverage_area_subtitle = $getMeta('coverage_area_subtitle');
    $coverage_area_title = $getMeta('coverage_area_title');
    $coverage_area_description = $getMeta('coverage_area_description');
    $coverage_area_areas = $normalizeTagValues($getMeta('coverage_area_areas'));
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
        <label class="form-label">Image Overlay Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_image_overlay_title]" value="{{ $breadcrumb_image_overlay_title }}" placeholder="Enter image overlay title" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Image Overlay Description <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_image_overlay_description]" value="{{ $breadcrumb_image_overlay_description }}" placeholder="Enter image overlay description" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Image Overlay Key Highlights <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[breadcrumb_image_overlay_key_highlights]" value="{{ $breadcrumb_image_overlay_key_highlights }}" placeholder="Enter key highlights separated by commas" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">How It Works Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[how_it_works_subtitle]" value="{{ $how_it_works_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[how_it_works_title]" value="{{ $how_it_works_title }}" placeholder="Enter title" required>
    </div>

    <div class="how-it-works-target col-md-12">
        @if(isset($how_it_works_items['itration']) && is_array($how_it_works_items['itration']))
            @foreach($how_it_works_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[how_it_works_items][itration][]" type="hidden" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[how_it_works_items][icon][]" class="selected-files" value="{{ $how_it_works_items['icon'][$index] ?? '' }}" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>

                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[how_it_works_items][title][]" value="{{ $how_it_works_items['title'][$index] ?? '' }}" placeholder="Enter title" required>
                            </div>

                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="meta[how_it_works_items][description][]" class="form-control" rows="3" placeholder="Enter description" required>{{ $how_it_works_items['description'][$index] ?? '' }}</textarea>
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
            data-limit="12"
            data-content='
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="data" name="meta[how_it_works_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[how_it_works_items][icon][]" class="selected-files" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="meta[how_it_works_items][title][]" class="form-control" placeholder="Enter title" required>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="meta[how_it_works_items][description][]" class="form-control" rows="3" placeholder="Enter description" required></textarea>
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
            data-target=".how-it-works-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Test Availability Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[test_availability_subtitle]" value="{{ $test_availability_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[test_availability_title]" value="{{ $test_availability_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Key Highlights <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[test_availability_key_highlights]" value="{{ $test_availability_key_highlights }}" placeholder="Enter key highlights separated by commas" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[test_availability_description]" class="form-control text-editor" rows="4" required>{{ $test_availability_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Coverage Area Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[coverage_area_subtitle]" value="{{ $coverage_area_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[coverage_area_title]" value="{{ $coverage_area_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[coverage_area_description]" class="form-control" rows="3" required>{{ $coverage_area_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Areas <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[coverage_area_areas]" value="{{ $coverage_area_areas }}" placeholder="Enter areas separated by commas" required>
    </div>
</div>
