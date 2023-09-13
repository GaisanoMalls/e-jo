@extends('layouts.user.ticket.view_ticket')

@section('count-replyThraeds-clarificafions')
{{ $ticket->clarifications->count() > 1 ? 'Clarifications' : 'Clarification' }}
({{ $ticket->clarifications->count() }})
@endsection

@section('ticket-reply-clarifications')
@livewire('requester.ticket-clarifications', ['ticket' => $ticket])
@endsection