<x-mail::message>

# {{ trans('NotificationMail.title') }}

{{ trans('NotificationMail.hello') }}, {{$user->name}}!

## {{ $training->name }} - {{ $training->date_start->format('d/m/Y') }} das {{ $training->date_start->format('H:m') }} ás {{ $training->date_end->format('H:m') }}

{{ $training->description }}

<br/>

{{ trans('NotificationMail.message_default') }}

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
