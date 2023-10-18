<x-mail::message>
# {{ $ticketNumber }}

<strong>{!! $ticketSubject !!}</strong>
<small>{!! $ticketDescription !!}</small>

<x-mail::button :url="$url">
View Ticket
</x-mail::button>

<small>Created by:</small><br>
<small>{{ $requesterFullName }} <em>({{ $requesterOtherInfo }})</em></small>
<hr>
<small>Approved by:</small><br>
<small>{{ $approver }} <em>(Service Dept. Admin)</em></small>
</x-mail::message>
