@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Purchase Tickets</h1>
    <form action="{{ route('tickets.purchase') }}" method="POST">
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
                @foreach($ticketTypes as $ticketType)
                    <option value="{{ $ticketType->id }}">{{ $ticketType->name }} - ${{ $ticketType->price }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
        </div>
        <div class="form-group">
            <label for="phone_number">Orange Money Phone Number</label>
            <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
        </div>
        <button type="submit" class="btn btn-primary">Purchase</button>
    </form>
</div>
@endsection
