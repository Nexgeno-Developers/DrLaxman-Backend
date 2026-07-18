@php
    use App\Models\Page;

    $getMeta = function ($key, $default = '') use ($pageData) {
        return $pageData->meta->where('meta_key', $key)->first()->meta_value ?? $default;
    };

    $breadcrumb_title = $getMeta('breadcrumb_title');
    $breadcrumb_subtitle = $getMeta('breadcrumb_subtitle');
    $breadcrumb_description = $getMeta('breadcrumb_description');

    $selectedCentres = json_decode($getMeta('centres', '[]'), true);
    $selectedCentres = is_array($selectedCentres) ? array_map('intval', $selectedCentres) : [];

    $centreOptions = Page::query()
        ->with('meta')
        ->where('layout', 'centre_detail')
        ->where('is_active', true)
        ->orderBy('title')
        ->get(['id', 'title']);

    $orderedCentreOptions = collect($selectedCentres)
        ->map(function ($selectedId) use ($centreOptions) {
            return $centreOptions->firstWhere('id', $selectedId);
        })
        ->filter()
        ->values()
        ->merge(
            $centreOptions->reject(function ($centre) use ($selectedCentres) {
                return in_array((int) $centre->id, $selectedCentres, true);
            })->values()
        );
@endphp

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Breadcrumb Section</h4>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_title]" value="{{ $breadcrumb_title }}" placeholder="Enter title" required>
    </div>

    <div class="col-md-6 form-group mb-2">
        <label class="form-label">Subtitle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="meta[breadcrumb_subtitle]" value="{{ $breadcrumb_subtitle }}" placeholder="Enter subtitle" required>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Description <span class="text-danger">*</span></label>
        <textarea name="meta[breadcrumb_description]" class="form-control text-editor" rows="4" required>{{ $breadcrumb_description }}</textarea>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <hr>
        <h4 class="text-primary">Centres Section</h4>
    </div>

    <div class="col-md-12 form-group mb-2">
        <label class="form-label">Centers <span class="text-danger">*</span></label>
        <select class="form-control select2 ordered-select2" name="meta[centres][]" multiple required>
            @foreach($orderedCentreOptions as $centre)
                @php
                    $centreType = $centre->meta->where('meta_key', 'type')->first()->meta_value ?? null;
                    $centreLabel = $centre->title . ($centreType ? ' (' . $centreType . ')' : '');
                @endphp
                <option value="{{ $centre->id }}" {{ in_array((int) $centre->id, $selectedCentres, true) ? 'selected' : '' }}>
                    {{ $centreLabel }}
                </option>
            @endforeach
        </select>
        <small class="text-muted d-block mt-1">Selected order will be saved and returned in the same order in the API.</small>
    </div>
</div>
