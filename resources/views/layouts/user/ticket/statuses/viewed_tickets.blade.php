@extends('layouts.user.base', ['title' => 'Viewed Tickets'])

@section('ticket-list-header')
<h5 class="mb-0 content__title">Viewed Tickets</h5>
@endsection

@section('main-content')
@livewire('requester.ticket-status.viewed')
@endsection