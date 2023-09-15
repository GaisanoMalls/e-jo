@extends('layouts.user.base', ['title' => 'Open Tickets'])

@section('ticket-list-header')
<h5 class="mb-0 content__title">Open Tickets</h5>
@endsection

@section('main-content')
@livewire('requester.ticket-status.open')
@endsection