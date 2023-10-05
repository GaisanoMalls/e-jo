@extends('layouts.staff.ticket.view_ticket')

@section('count-replyThreads-clarificafions')
@livewire('staff.ticket.load-ticket-clarifications-count', ['ticket' => $ticket])
@endsection

@section('ticket-reply-clarifications')
@livewire('staff.ticket-clarifications', ['ticket' => $ticket])
@endsection