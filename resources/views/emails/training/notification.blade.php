<x-mail::message>

# {{ trans('NotificationMail.title') }}

{{ trans('NotificationMail.hello') }}, {{$user->name}}!

## {{$title}}

## {{ trans('NotificationMail.description_training') }}:
{{ $training->description }}

<br/>

{{ trans('NotificationMail.message_default') }}

{{ trans('NotificationMail.message_please') }}

<x-mail::button :url="''">
    {{ trans('NotificationMail.answer_call') }}
</x-mail::button>

{{ trans('NotificationMail.good_training') }}!
<br>
<br>
{{ trans('NotificationMail.thanks') }},
<br>
{{ config('app.name') }}
</x-mail::message>
