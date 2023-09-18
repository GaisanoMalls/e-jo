@extends('layouts.staff.approver.base', ['title' => 'Approved Tickets'])

@section('main-content')
@livewire('approver.ticket-status.approved')
@endsection