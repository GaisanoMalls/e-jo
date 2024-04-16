@extends('layouts.user.feedback.index', ['title' => 'My Reviews'])

@section('feedback-content')
    @livewire('requester.ticket-feedback.my-feedbacks')
@endsection
