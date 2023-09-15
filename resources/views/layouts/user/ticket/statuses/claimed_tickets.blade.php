@extends('layouts.user.base', ['title' => 'On Process Tickets'])

@section('ticket-list-header')
<h5 class="mb-0 content__title">Claimed Tickets</h5>
@endsection

@section('main-content')
@livewire('requester.ticket-status.claimed')
@endsection