@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Ticket</h1>
    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="event_id">Event</label>
            <select class="form-control" id="event_id" name="event_id" required>
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="ticket_type_id">Ticket Type</label>
            <select class="form-control" id="ticket_type_id" name="ticket_type_id" required>
                @foreach($events as $event)
                    @foreach($event->ticketTypes as $ticketType)
                        <option value="{{ $ticketType->id }}">{{ $ticketType->name }} - ${{ $ticketType->price }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Ticket</button>
    </form>
</div>
@endsection
