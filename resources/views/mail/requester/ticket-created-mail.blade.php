<x-mail::message>
# {{ $newTicketMessage }}

{{ $message }}

<x-mail::button :url="$url">
View Ticket
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
