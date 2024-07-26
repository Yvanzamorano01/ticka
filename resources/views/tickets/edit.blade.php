@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Ticket</h1>
    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="type">Type</label>
            <input type="text" class="form-control" id="type" name="type" value="{{ $ticket->type }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ $ticket->price }}" required>
        </div>
        <div class="form-group">
            <label for="event_id">Event</label>
            <select class="form-control" id="event_id" name="event_id" required>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ $ticket->event_id == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
