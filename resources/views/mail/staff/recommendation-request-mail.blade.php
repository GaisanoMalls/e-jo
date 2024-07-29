<x-mail::message>
    # {{ $ticketNumber }}

    <strong>{!! $ticketSubject !!}</strong>

    <x-mail::button :url="$url">
        View Ticket
    </x-mail::button>

    <small>Requested by (Agent):</small><br>
    <small>{{ $agentFullName }}</small>
    <small>{{ $agentBUDept }} <em>({{ $agentBranch }})</em></small>
</x-mail::message>
