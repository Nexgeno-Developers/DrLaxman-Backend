@php
    $getMeta = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $getRepeater = function ($key) use ($getMeta) {
        $data = json_decode($getMeta($key, '[]'), true);

        return is_array($data) ? $data : [];
    };

    $breadcrumb_title = $getMeta('breadcrumb_title');
    $vision_title = $getMeta('vision_title');
    $vision_items = $getRepeater('vision_items');
    $mission_title = $getMeta('mission_title');
    $mission_items = $getRepeater('mission_items');
    $mission_vision_image = $getMeta('mission_vision_image');
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_title]" value="{{ $breadcrumb_title }}" placeholder="Enter title" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Vision Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[vision_title]" value="{{ $vision_title }}" placeholder="Enter vision title" required>
    </div>

    <div class="vision-items-target col-md-12">
        @if(isset($vision_items['itration']) && is_array($vision_items['itration']))
            @foreach($vision_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[vision_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[vision_items][description][]" value="{{ $vision_items['description'][$index] ?? '' }}" placeholder="Enter description" required>
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
                                <input value="data" name="meta[vision_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" name="meta[vision_items][description][]" class="form-control" placeholder="Enter description" required>
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
            data-target=".vision-items-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Mission Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[mission_title]" value="{{ $mission_title }}" placeholder="Enter mission title" required>
    </div>

    <div class="mission-items-target col-md-12">
        @if(isset($mission_items['itration']) && is_array($mission_items['itration']))
            @foreach($mission_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[mission_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[mission_items][description][]" value="{{ $mission_items['description'][$index] ?? '' }}" placeholder="Enter description" required>
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
                                <input value="data" name="meta[mission_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" name="meta[mission_items][description][]" class="form-control" placeholder="Enter description" required>
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
            data-target=".mission-items-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Mission & Vision Image</h4>
    </div>

    <div class="col-md-12">
        <label class="form-label">Image <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $mission_vision_image }}" type="hidden" name="meta[mission_vision_image]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>
</div>
