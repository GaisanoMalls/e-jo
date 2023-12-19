@extends('layouts.user.ticket.view_ticket')

@section('count-replyThreads-clarificafions')
    @livewire('requester.ticket.load-clarifications-count', ['ticket' => $ticket])
@endsection

@section('ticket-reply-clarifications')
    @livewire('requester.ticket-clarifications', ['ticket' => $ticket])
@endsection
