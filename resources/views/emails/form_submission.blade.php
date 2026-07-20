{{-- resources/views/emails/form_submission.blade.php --}}

@component('mail::message')
# {{ ucfirst(str_replace('_', ' ', $formName)) }} Form Submission

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
@foreach($data as $key => $value)
    <tr>
        <td valign="top" style="padding: 8px 12px 8px 0; font-weight: 700; width: 180px;">
            {{ ucwords(str_replace('_', ' ', $key)) }}
        </td>
        <td valign="top" style="padding: 8px 0;">
            @if(is_array($value))
                @foreach($value as $item)
                    <div style="margin-bottom: 6px;">
                        @if(is_string($item) && \Illuminate\Support\Str::startsWith($item, ['http://', 'https://']))
                            <a href="{{ $item }}">{{ $item }}</a>
                        @else
                            {{ is_scalar($item) ? $item : json_encode($item) }}
                        @endif
                    </div>
                @endforeach
            @else
                @if(is_string($value) && \Illuminate\Support\Str::startsWith($value, ['http://', 'https://']))
                    <a href="{{ $value }}">{{ $value }}</a>
                @else
                    {{ $value }}
                @endif
            @endif
        </td>
    </tr>
@endforeach
</table>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
