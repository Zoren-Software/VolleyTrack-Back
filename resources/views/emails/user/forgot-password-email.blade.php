<x-mail::message>

# {{ trans('ForgotPasswordMail.title_mail') }}

{{ trans('ForgotPasswordMail.hello') }}, {{$user->name}}!

{{ trans('ForgotPasswordMail.why') }}

{{ tenant('id') }} . {{ env('APP_HOST')}}

@php
    $link = env('APP_PROTOCOL') . '://' . env('APP_URL') . '/verify-email/' . tenant('id') . '/' . $user->set_password_token;
@endphp

<x-mail::button :url="$link">
    {{ trans('ForgotPasswordMail.button_forgot_password') }}
</x-mail::button>

<br>

{{ trans('ForgotPasswordMail.message_support') }}

<br>
{{ trans('ForgotPasswordMail.thanks') }},
<br>
{{ config('app.name') }}
</x-mail::message>
