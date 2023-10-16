<x-mail::message>
# {{ $ticketNumber }}

<strong>{!! $ticketSubject !!}</strong>
<small>{!! $ticketDescription !!}</small>

<small>Created by:</small><br>
<small>{{ $requesterFullName }} <em>({{ $requesterOtherInfo }})</em></small>
<hr>
<small>Approved by:</small><br>
<small>{{ $approver }} <em>(Service Dept. Admin)</em></small>
</x-mail::message>