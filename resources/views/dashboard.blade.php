@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="card">
    <div class="card-header">Welcome to MOE LIMS</div>
    <div class="card-body">
        <p>Use the left menu to navigate.</p>
        <p>Logged in as <strong>{{ Auth::user()->name }}</strong> ({{ Auth::user()->role }})</p>
    </div>
</div>
@endsection