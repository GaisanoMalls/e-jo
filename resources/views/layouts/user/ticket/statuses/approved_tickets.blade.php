@extends('layouts.user.base', ['title' => 'Approved Tickets'])

@section('ticket-list-header')
    <h5 class="mb-0 content__title">Approved Tickets</h5>
    @livewire('requester.ticket-status.sort.sort-approved-tickets')
@endsection

@section('main-content')
    @livewire('requester.ticket-status.approved')
@endsection
