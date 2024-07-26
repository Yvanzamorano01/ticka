@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tickets</h1>
    <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create Ticket</a>
    <table class="table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Price</th>
                <th>Event</th>
                <th>QR Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->type }}</td>
                <td>{{ $ticket->price }}</td>
                <td>{{ $ticket->event->name }}</td>
                <td><img src="{{ asset('storage/' . $ticket->qr_code_path) }}" alt="QR Code" width="100"></td>
                <td>
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
