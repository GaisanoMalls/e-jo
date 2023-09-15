@extends('layouts.user.base', ['title' => 'Closed Tickets'])

@section('ticket-list-header')
<h5 class="mb-0 content__title">Closed Tickets</h5>
@endsection

@section('main-content')
@livewire('requester.ticket-status.closed')
@endsection