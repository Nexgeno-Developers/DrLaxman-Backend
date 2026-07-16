{{-- resources/views/emails/form_submission.blade.php --}}

@component('mail::message')
# {{ ucfirst(str_replace('_', ' ', $formName)) }} Form Submission

@foreach($data as $key => $value)
**{{ ucwords(str_replace('_', ' ', $key)) }}:**
@if(is_array($value))
@foreach($value as $item)
- @if(is_string($item) && \Illuminate\Support\Str::startsWith($item, ['http://', 'https://']))[{{ $item }}]({{ $item }})@else{{ is_scalar($item) ? $item : json_encode($item) }}@endif
@endforeach
@else
@if(is_string($value) && \Illuminate\Support\Str::startsWith($value, ['http://', 'https://']))[{{ $value }}]({{ $value }})@else{{ $value }}@endif
@endif

@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
