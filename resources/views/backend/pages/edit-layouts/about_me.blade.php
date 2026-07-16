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
    $breadcrumb_tagline = $getMeta('breadcrumb_tagline');
    $breadcrumb_key_highlights = $normalizeTagValues($getMeta('breadcrumb_key_highlights'));
    $breadcrumb_overview = $getMeta('breadcrumb_overview');
    $breadcrumb_picture = $getMeta('breadcrumb_picture');
    $breadcrumb_picture_overlay_title = $getMeta('breadcrumb_picture_overlay_title');
    $breadcrumb_picture_overlay_description = $getMeta('breadcrumb_picture_overlay_description');

    $credentials_subtitle = $getMeta('credentials_subtitle');
    $credentials_title = $getMeta('credentials_title');
    $credentials_items = $getRepeater('credentials_items');

    $our_story_subtitle = $getMeta('our_story_subtitle');
    $our_story_title = $getMeta('our_story_title');
    $our_story_description = $getMeta('our_story_description');
    $our_story_image_1 = $getMeta('our_story_image_1');
    $our_story_image_2 = $getMeta('our_story_image_2');
    $our_story_items = $getRepeater('our_story_items');

    $academic_figure_subtitle = $getMeta('academic_figure_subtitle');
    $academic_figure_title = $getMeta('academic_figure_title');

    $feature_description = $getMeta('feature_description');

    $why_choose_subtitle = $getMeta('why_choose_subtitle');
    $why_choose_title = $getMeta('why_choose_title');
    $why_choose_description = $getMeta('why_choose_description');
    $why_choose_items = $getRepeater('why_choose_items');

    $trust_title = $getMeta('trust_title');
    $trust_key_highlights = $normalizeTagValues($getMeta('trust_key_highlights'));

    $centres_title = $getMeta('centres_title');
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

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Tagline <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_tagline]" value="{{ $breadcrumb_tagline }}" placeholder="Enter tagline" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Key Highlights <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[breadcrumb_key_highlights]" value="{{ $breadcrumb_key_highlights }}" placeholder="Enter key highlights separated by commas" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Overview <span class="text-danger">*</span></label>
        <textarea name="meta[breadcrumb_overview]" class="form-control text-editor" rows="4" required>{{ $breadcrumb_overview }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Picture <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $breadcrumb_picture }}" type="hidden" name="meta[breadcrumb_picture]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Picture Overlay Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_picture_overlay_title]" value="{{ $breadcrumb_picture_overlay_title }}" placeholder="Enter picture overlay title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Picture Overlay Description <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_picture_overlay_description]" value="{{ $breadcrumb_picture_overlay_description }}" placeholder="Enter picture overlay description" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Credentials Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[credentials_subtitle]" value="{{ $credentials_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[credentials_title]" value="{{ $credentials_title }}" placeholder="Enter title" required>
    </div>

    <div class="credentials-items-target col-md-12">
        @if(isset($credentials_items['itration']) && is_array($credentials_items['itration']))
            @foreach($credentials_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[credentials_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[credentials_items][icon][]" class="selected-files" value="{{ $credentials_items['icon'][$index] ?? '' }}" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[credentials_items][title][]" value="{{ $credentials_items['title'][$index] ?? '' }}" placeholder="Enter title" required>
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
                                <input value="data" name="meta[credentials_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[credentials_items][icon][]" class="selected-files" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="meta[credentials_items][title][]" class="form-control" placeholder="Enter title" required>
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
            data-target=".credentials-items-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Our Story Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[our_story_subtitle]" value="{{ $our_story_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[our_story_title]" value="{{ $our_story_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[our_story_description]" class="form-control text-editor" rows="4" required>{{ $our_story_description }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label">Image 1 <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $our_story_image_1 }}" type="hidden" name="meta[our_story_image_1]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Image 2 <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $our_story_image_2 }}" type="hidden" name="meta[our_story_image_2]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="our-story-items-target col-md-12">
        @if(isset($our_story_items['itration']) && is_array($our_story_items['itration']))
            @foreach($our_story_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[our_story_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Counts <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[our_story_items][counts][]" value="{{ $our_story_items['counts'][$index] ?? '' }}" placeholder="Enter counts" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[our_story_items][title][]" value="{{ $our_story_items['title'][$index] ?? '' }}" placeholder="Enter title" required>
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
                                <input value="data" name="meta[our_story_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Counts <span class="text-danger">*</span></label>
                                <input type="text" name="meta[our_story_items][counts][]" class="form-control" placeholder="Enter counts" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="meta[our_story_items][title][]" class="form-control" placeholder="Enter title" required>
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
            data-target=".our-story-items-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Academic Figure Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[academic_figure_subtitle]" value="{{ $academic_figure_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[academic_figure_title]" value="{{ $academic_figure_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label d-block text-center">Latest events will be automatically fetched from Events</label>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Feature Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[feature_description]" class="form-control text-editor" rows="4" required>{{ $feature_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Why Choose Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[why_choose_subtitle]" value="{{ $why_choose_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[why_choose_title]" value="{{ $why_choose_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[why_choose_description]" class="form-control text-editor" rows="4" required>{{ $why_choose_description }}</textarea>
    </div>

    <div class="why-choose-items-target col-md-12">
        @if(isset($why_choose_items['itration']) && is_array($why_choose_items['itration']))
            @foreach($why_choose_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[why_choose_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[why_choose_items][icon][]" class="selected-files" value="{{ $why_choose_items['icon'][$index] ?? '' }}" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-8 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[why_choose_items][title][]" value="{{ $why_choose_items['title'][$index] ?? '' }}" placeholder="Enter title" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="meta[why_choose_items][description][]" class="form-control" rows="3" placeholder="Enter description" required>{{ $why_choose_items['description'][$index] ?? '' }}</textarea>
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
                                <input value="data" name="meta[why_choose_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[why_choose_items][icon][]" class="selected-files" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-8 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="meta[why_choose_items][title][]" class="form-control" placeholder="Enter title" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="meta[why_choose_items][description][]" class="form-control" rows="3" placeholder="Enter description" required></textarea>
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
            data-target=".why-choose-items-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Trust Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[trust_title]" value="{{ $trust_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Key Highlights <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[trust_key_highlights]" value="{{ $trust_key_highlights }}" placeholder="Enter key highlights separated by commas" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Centres Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[centres_title]" value="{{ $centres_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label d-block text-center">Centres will be automatically fetched from centre detials</label>
    </div>
</div>
