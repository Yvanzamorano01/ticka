@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>
    <p>{{ $event->description }}</p>
    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('Y-m-d H:i') }}</p>
    <p><strong>Location:</strong> {{ $event->location }}</p>
    <h3>Ticket Types</h3>
    <ul>
        @foreach($event->ticketTypes as $ticketType)
            <li>{{ $ticketType->name }}: ${{ $ticketType->price }}</li>
        @endforeach
    </ul>
    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
    <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
</div>
@endsection
