@component('mail::message')

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

@isset($security_code)
<div style="padding:6px;background-color:#000;color:#fff">
{{ $security_code }}
</div>
@endisset

@endcomponent
