<x-mail::message>

# {{ trans('NotificationMail.title_create_account') }}

{{ trans('NotificationMail.hello') }}, {{$user->name}}!

<br/>

{{ trans('NotificationMail.confirm_email_and_password') }}:

<x-mail::button :url="route('verify.email', [
    'token' => $user->remember_token,
    'tenant' => $tenant
])">
    {{ trans('NotificationMail.button_confirm_email_and_create_password') }}
</x-mail::button>

{{ trans('NotificationMail.good_training') }}!
<br>
<br>
{{ trans('NotificationMail.thanks') }},
<br>
{{ config('app.name') }}
</x-mail::message>