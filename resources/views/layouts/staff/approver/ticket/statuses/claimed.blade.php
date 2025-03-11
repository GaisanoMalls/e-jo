@extends('layouts.staff.approver.base', ['title' => 'Closed'])

@section('main-content')
    @livewire('approver.ticket-status.claimed')
@endsection
