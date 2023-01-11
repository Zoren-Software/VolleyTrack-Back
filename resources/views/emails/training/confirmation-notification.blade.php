<x-mail::message>

# {{ trans('NotificationMail.title_confirmation') }}

{{ trans('NotificationMail.hello') }}, {{$user->name}}!

## {{ $training->name }} - {{ $training->date_start->format('d/m/Y') }} das {{ $training->date_start->format('H:m') }} ás {{ $training->date_end->format('H:m') }}

<br/>

## {{ trans('NotificationMail.description_training') }}:
{{ $training->description }}

<br/>

## {{ trans('NotificationMail.list_of_training_players') }}:

@foreach ($training->team->players as $player)
    - {{ $player->name }}

@endforeach
<br/>

___
<br/>
<br/>

{{ trans('NotificationMail.message_default_technician') }}

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
