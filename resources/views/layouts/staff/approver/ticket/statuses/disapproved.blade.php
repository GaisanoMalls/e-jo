@extends('layouts.staff.approver.base', ['title' => 'Disapproved Tickets'])

@section('main-content')
@livewire('approver.ticket-status.disapproved')
@endsection