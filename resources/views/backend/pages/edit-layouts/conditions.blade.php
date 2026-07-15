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
        if (is_array($value)) {
            return collect($value)
                ->map(function ($item) {
                    if (is_array($item)) {
                        return trim((string) ($item['value'] ?? ''));
                    }

                    return trim((string) $item);
                })
                ->filter()
                ->implode(',');
        }

        return (string) $value;
    };

    $breadcrumb_title = $getMeta('breadcrumb_title');
    $breadcrumb_subtitle = $getMeta('breadcrumb_subtitle');
    $breadcrumb_description = $getMeta('breadcrumb_description');
    $breadcrumb_key_highlights = $getRepeater('breadcrumb_key_highlights');

    $overview_title = $getMeta('overview_title');
    $overview_image = $getMeta('overview_image');
    $overview_description = $getMeta('overview_description');

    $symptoms_title = $getMeta('symptoms_title');
    $symptoms = $normalizeTagValues($getMeta('symptoms'));

    $types_stages_title = $getMeta('types_stages_title');
    $types_stages_items = $getRepeater('types_stages_items');

    $common_causes_title = $getMeta('common_causes_title');
    $common_causes = $normalizeTagValues($getMeta('common_causes'));

    $treatment_title = $getMeta('treatment_title');
    $treatment_items = $getRepeater('treatment_items');
    $treatment_recommendation = $getMeta('treatment_recommendation');

    $why_treat_title = $getMeta('why_treat_title');
    $why_treat_description = $getMeta('why_treat_description');

    $recovery_timeline_title = $getMeta('recovery_timeline_title');
    $recovery_timeline_items = $getRepeater('recovery_timeline_items');

    $cost_insurance_title = $getMeta('cost_insurance_title');
    $cost_insurance_description = $getMeta('cost_insurance_description');
    $cost_insurance_centres = $getMeta('cost_insurance_centres');

    $faq_title = $getMeta('faq_title');
    $faq_items = $getRepeater('faq_items');

    $selectedConditions = json_decode($getMeta('conditions', '[]'), true);
    $selectedConditions = is_array($selectedConditions) ? array_map('intval', $selectedConditions) : [];
    $conditionOptions = Page::query()
        ->where('layout', 'conditions')
        ->where('is_active', true)
        ->where('id', '!=', $pageData->id)
        ->orderBy('title')
        ->get(['id', 'title']);
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_title]" value="{{ $breadcrumb_title }}" placeholder="Enter breadcrumb title" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_subtitle]" value="{{ $breadcrumb_subtitle }}" placeholder="Enter breadcrumb subtitle" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[breadcrumb_description]" class="form-control text-editor" rows="4" required>{{ $breadcrumb_description }}</textarea>
    </div>

    <div class="col-md-12">
        <hr>
        <h5 class="text-primary">Key Highlights</h5>
    </div>

    <div class="breadcrumb-highlights-target col-md-12">
        @if(isset($breadcrumb_key_highlights['itration']) && is_array($breadcrumb_key_highlights['itration']))
            @foreach($breadcrumb_key_highlights['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[breadcrumb_key_highlights][itration][]" type="hidden" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[breadcrumb_key_highlights][icon][]" class="selected-files" value="{{ $breadcrumb_key_highlights['icon'][$index] ?? '' }}" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>

                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Value <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[breadcrumb_key_highlights][value][]" value="{{ $breadcrumb_key_highlights['value'][$index] ?? '' }}" placeholder="Enter highlight value" required>
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
            data-limit="10"
            data-content='
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="data" name="meta[breadcrumb_key_highlights][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Icon <span class="text-danger">*</span></label>
                                <div class="form-group mb-2">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ __('Choose File') }}</div>
                                        <input type="hidden" name="meta[breadcrumb_key_highlights][icon][]" class="selected-files" required>
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Value <span class="text-danger">*</span></label>
                                <input type="text" name="meta[breadcrumb_key_highlights][value][]" class="form-control" placeholder="Enter highlight value" required>
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
            data-target=".breadcrumb-highlights-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Overview Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[overview_title]" value="{{ $overview_title }}" placeholder="Enter overview title" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Image <span class="text-danger">*</span></label>
        <div class="form-group mb-2">
            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                <div class="input-group-prepend">
                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ __('Browse') }}</div>
                </div>
                <div class="form-control file-amount">{{ __('Choose File') }}</div>
                <input value="{{ $overview_image }}" type="hidden" name="meta[overview_image]" class="selected-files" required>
            </div>
            <div class="file-preview box sm"></div>
        </div>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[overview_description]" class="form-control text-editor" rows="4" required>{{ $overview_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Symptoms Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[symptoms_title]" value="{{ $symptoms_title }}" placeholder="Enter symptoms title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Symptoms <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[symptoms]" value="{{ $symptoms }}" placeholder="Enter symptoms separated by commas" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Types & Stages Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[types_stages_title]" value="{{ $types_stages_title }}" placeholder="Enter types & stages title" required>
    </div>

    <div class="types-stages-target col-md-12">
        @if(isset($types_stages_items['itration']) && is_array($types_stages_items['itration']))
            @foreach($types_stages_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[types_stages_items][itration][]" type="hidden" required>
                            </div>

                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[types_stages_items][title][]" value="{{ $types_stages_items['title'][$index] ?? '' }}" placeholder="Enter stage title" required>
                            </div>

                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[types_stages_items][description][]" value="{{ $types_stages_items['description'][$index] ?? '' }}" placeholder="Enter stage description" required>
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
                                <input value="data" name="meta[types_stages_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="meta[types_stages_items][title][]" class="form-control" placeholder="Enter stage title" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" name="meta[types_stages_items][description][]" class="form-control" placeholder="Enter stage description" required>
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
            data-target=".types-stages-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Common Causes Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[common_causes_title]" value="{{ $common_causes_title }}" placeholder="Enter common causes title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Symptoms <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[common_causes]" value="{{ $common_causes }}" placeholder="Enter causes separated by commas" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Treatment Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[treatment_title]" value="{{ $treatment_title }}" placeholder="Enter treatment title" required>
    </div>

    <div class="treatment-target col-md-12">
        @if(isset($treatment_items['itration']) && is_array($treatment_items['itration']))
            @foreach($treatment_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[treatment_items][itration][]" type="hidden" required>
                            </div>

                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="meta[treatment_items][description][]" class="form-control text-editor" rows="3" required>{{ $treatment_items['description'][$index] ?? '' }}</textarea>
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
                                <input value="data" name="meta[treatment_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-12 form-group mb-2">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="meta[treatment_items][description][]" class="form-control text-editor" rows="3" required></textarea>
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
            data-target=".treatment-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Recommendation <span class="text-danger">*</span></label>
        <textarea name="meta[treatment_recommendation]" class="form-control text-editor" rows="4" required>{{ $treatment_recommendation }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Why Treat Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[why_treat_title]" value="{{ $why_treat_title }}" placeholder="Enter why treat title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[why_treat_description]" class="form-control text-editor" rows="4" required>{{ $why_treat_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Recovery Timeline Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[recovery_timeline_title]" value="{{ $recovery_timeline_title }}" placeholder="Enter recovery timeline title" required>
    </div>

    <div class="recovery-timeline-target col-md-12">
        @if(isset($recovery_timeline_items['itration']) && is_array($recovery_timeline_items['itration']))
            @foreach($recovery_timeline_items['itration'] as $index => $itration)
                <div class="row remove-parent">
                    <div class="col-md-11">
                        <div class="row">
                            <div class="col-md-12">
                                <input value="{{ $index }}" name="meta[recovery_timeline_items][itration][]" type="hidden" required>
                            </div>

                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Count <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[recovery_timeline_items][count][]" value="{{ $recovery_timeline_items['count'][$index] ?? '' }}" placeholder="Enter count" required>
                            </div>

                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meta[recovery_timeline_items][title][]" value="{{ $recovery_timeline_items['title'][$index] ?? '' }}" placeholder="Enter timeline title" required>
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
                                <input value="data" name="meta[recovery_timeline_items][itration][]" type="hidden" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Count <span class="text-danger">*</span></label>
                                <input type="text" name="meta[recovery_timeline_items][count][]" class="form-control" placeholder="Enter count" required>
                            </div>
                            <div class="col-md-6 form-group mb-2">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="meta[recovery_timeline_items][title][]" class="form-control" placeholder="Enter timeline title" required>
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
            data-target=".recovery-timeline-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Cost & Insurance Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[cost_insurance_title]" value="{{ $cost_insurance_title }}" placeholder="Enter cost & insurance title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[cost_insurance_description]" class="form-control text-editor" rows="4" required>{{ $cost_insurance_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Check Insurance & Centres <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[cost_insurance_centres]" value="{{ $cost_insurance_centres }}" placeholder="Enter centres or check insurance text" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">FAQ Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[faq_title]" value="{{ $faq_title }}" placeholder="Enter FAQ title" required>
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
            '
            data-target=".faq-target">
            <i class="ti ti-plus"></i>
            <span class="ml-2">Add More</span>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Related Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Conditions <span class="text-danger">*</span></label>
        <select class="form-control select2" name="meta[conditions][]" multiple required>
            @foreach($conditionOptions as $condition)
                <option value="{{ $condition->id }}" {{ in_array((int) $condition->id, $selectedConditions, true) ? 'selected' : '' }}>
                    {{ $condition->title }}
                </option>
            @endforeach
        </select>
    </div>
</div>
