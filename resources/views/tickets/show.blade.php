@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ticket Details</h1>
    <div class="card">
        <div class="card-header">
            Ticket ID: {{ $ticket->id }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Type: {{ $ticket->type }}</h5>
            <p class="card-text">Price: ${{ $ticket->price }}</p>
            <p class="card-text">Event: {{ $ticket->event->name }}</p>
            <p class="card-text">QR Code:</p>
            <img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code">
        </div>
    </div>
    <a href="{{ route('tickets.index') }}" class="btn btn-primary mt-3">Back to Tickets</a>
</div>
@endsection
