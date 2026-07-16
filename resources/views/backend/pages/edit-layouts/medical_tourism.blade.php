@php
    $getMeta = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $breadcrumb_subtitle = $getMeta('breadcrumb_subtitle');
    $breadcrumb_title = $getMeta('breadcrumb_title');

    $arrangements_title = $getMeta('arrangements_title');
    $arrangements_description = $getMeta('arrangements_description');
    $arrangements_image = $getMeta('arrangements_image');
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
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Arrangements Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[arrangements_title]" value="{{ $arrangements_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[arrangements_description]" class="form-control text-editor" rows="4" required>{{ $arrangements_description }}</textarea>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $arrangements_image }}" type="hidden" name="meta[arrangements_image]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
</div>
