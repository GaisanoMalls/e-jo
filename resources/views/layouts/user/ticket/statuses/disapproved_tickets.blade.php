@extends('layouts.user.base', ['title' => 'Disapproved Tickets'])

@section('ticket-list-header')
    <h5 class="mb-0 content__title">Disapproved Tickets</h5>
@endsection

@section('main-content')
    @livewire('requester.ticket-status.disapproved')
@endsection
