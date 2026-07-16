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

    $teams_subtitle = $getMeta('teams_subtitle');
    $teams_title = $getMeta('teams_title');
    $team_items = $getRepeater('team_items');
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

    <div class="col-md-12">
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
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Teams Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[teams_subtitle]" value="{{ $teams_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[teams_title]" value="{{ $teams_title }}" placeholder="Enter title" required>
    </div>

    <div class="team-items-target col-md-12">
        @if(isset($team_items['itration']) && is_array($team_items['itration']))
            @foreach($team_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[team_items][itration][]" type="hidden" required>
                            </div>

                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Badge Text <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[team_items][badge_text][]" value="{{ $team_items['badge_text'][$index] ?? '' }}" placeholder="Enter badge text" required>
                            </div>

                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[team_items][name][]" value="{{ $team_items['name'][$index] ?? '' }}" placeholder="Enter name" required>
                            </div>

                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[team_items][designation][]" value="{{ $team_items['designation'][$index] ?? '' }}" placeholder="Enter designation" required>
                            </div>

                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Short Bio <span class="text-danger">*</span></label>
                                <textarea name="meta[team_items][short_bio][]" class="form-control" rows="3" placeholder="Enter short bio" required>{{ $team_items['short_bio'][$index] ?? '' }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Profile Image <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[team_items][profile_image][]" class="selected-files" value="{{ $team_items['profile_image'][$index] ?? '' }}" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>

                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Specializations <span class="text-danger">*</span></label>
                                <input type="text" class="form-control aiz-tag-input" name="meta[team_items][specializations][]" value="{{ $normalizeTagValues($team_items['specializations'][$index] ?? '') }}" placeholder="Enter specializations separated by commas" required>
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
                                <input value="data" name="meta[team_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Badge Text <span class="text-danger">*</span></label>
                                <input type="text" name="meta[team_items][badge_text][]" class="form-control" placeholder="Enter badge text" required>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="meta[team_items][name][]" class="form-control" placeholder="Enter name" required>
                            </div>
                            <div class="col-md-4 form-group mb-2">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <input type="text" name="meta[team_items][designation][]" class="form-control" placeholder="Enter designation" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Short Bio <span class="text-danger">*</span></label>
                                <textarea name="meta[team_items][short_bio][]" class="form-control" rows="3" placeholder="Enter short bio" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Profile Image <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[team_items][profile_image][]" class="selected-files" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Specializations <span class="text-danger">*</span></label>
                                <input type="text" name="meta[team_items][specializations][]" class="form-control aiz-tag-input" placeholder="Enter specializations separated by commas" required>
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
            data-target=".team-items-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>
