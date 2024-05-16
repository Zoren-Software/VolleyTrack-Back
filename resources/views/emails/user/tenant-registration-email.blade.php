<x-mail::message>

# {{ trans('TenantRegistrationMail.title_mail') }}

{{ trans('TenantRegistrationMail.hello') }}, {{$user->name}}!

{{ trans('TenantRegistrationMail.why') }}

{{ tenant('id') }} . {{ env('APP_HOST')}}

@php
    $link = env('APP_PROTOCOL') . '://' . env('APP_URL') . '/verify-email/' . tenant('id') . '/' . $user->set_password_token;
@endphp

<x-mail::button :url="$link">
    {{ trans('NotificationMail.button_confirm_email_and_create_password') }}
</x-mail::button>

<br>

{{ trans('TenantRegistrationMail.message_support') }}

<br>
{{ trans('TenantRegistrationMail.thanks') }},
<br>
{{ config('app.name') }}
</x-mail::message>
