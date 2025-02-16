@extends('layouts.staff.ticket.view_ticket')

@section('ticket-reply-clarifications-subtasks')
    @livewire('staff.ticket.ticket-subtask', ['ticket' => $ticket])
@endsection
