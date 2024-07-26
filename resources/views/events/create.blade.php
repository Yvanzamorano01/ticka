@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Event</h1>
    <form action="{{ route('events.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="event_date">Date</label>
            <input type="datetime-local" class="form-control" id="event_date" name="event_date" required>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" required>
        </div>
        <div id="ticket-types">
            <h3>Ticket Types</h3>
            <div class="form-group">
                <label for="ticket_types[0][name]">Ticket Type</label>
                <input type="text" class="form-control" id="ticket_types[0][name]" name="ticket_types[0][name]" required>
            </div>
            <div class="form-group">
                <label for="ticket_types[0][price]">Ticket Price</label>
                <input type="number" step="0.01" class="form-control" id="ticket_types[0][price]" name="ticket_types[0][price]" required>
            </div>
        </div>
        <button type="button" id="add-ticket-type" class="btn btn-secondary">Add Ticket Type</button>
        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>

<script>
document.getElementById('add-ticket-type').addEventListener('click', function() {
    const ticketTypesDiv = document.getElementById('ticket-types');
    const index = ticketTypesDiv.querySelectorAll('.form-group').length / 2;
    const newTicketType = `
        <div class="form-group">
            <label for="ticket_types[${index}][name]">Ticket Type</label>
            <input type="text" class="form-control" id="ticket_types[${index}][name]" name="ticket_types[${index}][name]" required>
        </div>
        <div class="form-group">
            <label for="ticket_types[${index}][price]">Ticket Price</label>
            <input type="number" step="0.01" class="form-control" id="ticket_types[${index}][price]" name="ticket_types[${index}][price]" required>
        </div>
    `;
    ticketTypesDiv.insertAdjacentHTML('beforeend', newTicketType);
});
</script>
@endsection
