@extends('layouts.user.ticket.view_ticket')

@section('count-replyThraeds-clarificafions')
@livewire('requester.ticket.load-clarifications-count', ['ticket' => $ticket])
@endsection

@section('ticket-reply-clarifications')
@livewire('requester.ticket-clarifications', ['ticket' => $ticket])
@endsection