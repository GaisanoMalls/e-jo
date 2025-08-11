@extends('layouts.auth.base', ['title' => ''])

@section('form-title')
    <h4 class="mb-2 mt-3">Set a new password</h4>
@endsection

@section('form-section')
    @livewire('auth.force-change-password')
@endsection


