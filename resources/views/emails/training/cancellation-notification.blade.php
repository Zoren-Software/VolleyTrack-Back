<x-mail::message>

# {{ trans('NotificationMail.title_cancel') }}

{{ trans('NotificationMail.hello') }}, {{$user->name}}!

## {{$title}}

## {{ trans('NotificationMail.description_training') }}:
{{ $training->description }}

<br/>

{{ trans('NotificationMail.message_default_cancellation') }}

{{ trans('NotificationMail.message_default') }}

{{ trans('NotificationMail.good_cancellation') }}!
<br>
<br>
{{ trans('NotificationMail.thanks') }},
<br>
{{ config('app.name') }}
</x-mail::message>
