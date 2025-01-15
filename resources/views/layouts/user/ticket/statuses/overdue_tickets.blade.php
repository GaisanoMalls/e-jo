@extends('layouts.user.base', ['title' => 'Open Tickets'])

@section('ticket-list-header')
    <h5 class="content__title mb-0">Open Tickets</h5>
@endsection

@section('main-content')
    @livewire('requester.ticket-status.overdue')
@endsection
