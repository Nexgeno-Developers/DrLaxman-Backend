@php
    use App\Models\Page;

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

    $breadcrumb_centre_name = $getMeta('breadcrumb_centre_name');
    $centre_type = $getMeta('type');
    $breadcrumb_title = $getMeta('breadcrumb_title');
    $breadcrumb_description = $getMeta('breadcrumb_description');
    $breadcrumb_image = $getMeta('breadcrumb_image');

    $address_map_iframe = $getMeta('address_map_iframe');
    $address_address = $getMeta('address_address');
    $address_timing = $getMeta('address_timing');
    $address_parking = $getMeta('address_parking');
    $address_direction = $getMeta('address_direction');

    $nearby_place_title = $getMeta('nearby_place_title');
    $nearby_place_description = $getMeta('nearby_place_description');
    $nearby_place_locations = $normalizeTagValues($getMeta('nearby_place_locations'));
    $nearby_place_transport = $getMeta('nearby_place_transport');

    $insurance_title = $getMeta('insurance_title');
    $insurance_description = $getMeta('insurance_description');
    $insurance_tagline = $getMeta('insurance_tagline');

    $selectedConditions = json_decode($getMeta('conditions', '[]'), true);
    $selectedConditions = is_array($selectedConditions) ? array_map('intval', $selectedConditions) : [];

    $conditionOptions = Page::query()
        ->whereIn('layout', ['conditions', 'condition_details'])
        ->where('is_active', true)
        ->orderBy('title')
        ->get(['id', 'title']);
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Centre Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_centre_name]" value="{{ $breadcrumb_centre_name }}" placeholder="Enter centre name" required>
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
        <h4 class="text-primary">Address Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Map Iframe <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[address_map_iframe]" value="{{ $address_map_iframe }}" placeholder="Enter map iframe" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Address <span class="text-danger">*</span></label>
        <textarea name="meta[address_address]" class="form-control text-editor" rows="4" required>{{ $address_address }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Timing <span class="text-danger">*</span></label>
        <textarea name="meta[address_timing]" class="form-control text-editor" rows="4" required>{{ $address_timing }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Parking <span class="text-danger">*</span></label>
        <textarea name="meta[address_parking]" class="form-control text-editor" rows="4" required>{{ $address_parking }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Direction <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[address_direction]" value="{{ $address_direction }}" placeholder="Enter direction" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Nearby Place Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[nearby_place_title]" value="{{ $nearby_place_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[nearby_place_description]" class="form-control text-editor" rows="4" required>{{ $nearby_place_description }}</textarea>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Locations <span class="text-danger">*</span></label>
        <input type="text" class="form-control aiz-tag-input" name="meta[nearby_place_locations]" value="{{ $nearby_place_locations }}" placeholder="Enter locations separated by commas" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Transport <span class="text-danger">*</span></label>
        <textarea name="meta[nearby_place_transport]" class="form-control text-editor" rows="4" required>{{ $nearby_place_transport }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Insurance Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[insurance_title]" value="{{ $insurance_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[insurance_description]" class="form-control text-editor" rows="4" required>{{ $insurance_description }}</textarea>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Tagline <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[insurance_tagline]" value="{{ $insurance_tagline }}" placeholder="Enter tagline" required>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Conditions Treatment Section</h4>
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

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Type Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Type <span class="text-danger">*</span></label>
        <select class="form-control select2" name="meta[type]" required>
            <option value="">Select type</option>
            <option value="Consulting" {{ $centre_type === 'Consulting' ? 'selected' : '' }}>Consulting</option>
            <option value="Operating" {{ $centre_type === 'Operating' ? 'selected' : '' }}>Operating</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Patient Reviews Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label d-block text-center">Reviews will be automatically fetched from Google My Business.</label>
    </div>
</div>
