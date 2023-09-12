<x-mail::message>
# {{ $ticketSubject }}

{!! $message !!}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
