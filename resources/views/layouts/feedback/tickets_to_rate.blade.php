@extends('layouts.feedback.index', ['title' => 'Tickets to rate'])

@section('feedback-content')
    @livewire('requester.ticket-feedback.ticket-list')
@endsection
