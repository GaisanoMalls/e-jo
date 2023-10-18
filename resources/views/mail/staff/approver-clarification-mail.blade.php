<x-mail::message>
# {{ $ticketSubject }}

{!! $message !!}

<x-mail::button :url="$url">
View Ticket
</x-mail::button>

-{{ $sender }}
</x-mail::message>
