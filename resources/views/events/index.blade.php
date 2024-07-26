@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Events</h1>
    <a href="{{ route('events.create') }}" class="btn btn-primary">Create Event</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Location</th>
                <th>Ticket Types</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <td>{{ $event->name }}</td>
                <td>{{ $event->description }}</td>
                <td>{{ $event->event_date }}</td>
                <td>{{ $event->location }}</td>
                <td>
                    @foreach($event->ticketTypes as $ticketType)
                        <p>{{ $ticketType->name }}: ${{ $ticketType->price }}</p>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" style="display:inline;">
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
