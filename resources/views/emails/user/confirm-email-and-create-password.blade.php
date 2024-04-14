<x-mail::message>

# {{ trans('NotificationMail.title_create_account') }}

{{ trans('NotificationMail.hello') }}, {{$user->name}}!

<br/>

{{ trans('NotificationMail.confirm_email_and_password') }}:

<x-mail::button :url="route('verify.email', [
    'token' => $user->set_password_token,
    'tenant' => $tenant,
    'email' => $user->email
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
