@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Payment</h1>
    <p>Please proceed to make the payment via Orange Money for the following tickets:</p>
    <ul>
        @foreach($tickets as $ticket)
            <li>Ticket ID: {{ $ticket['id'] }}, Type: {{ $ticket['type'] }}, Price: ${{ $ticket['price'] }}</li>
        @endforeach
    </ul>
    <p>Total Amount: ${{ $amount }}</p>
    <form action="{{ route('tickets.payment.process') }}" method="POST">
        @csrf
        <input type="hidden" name="tickets" value="{{ json_encode($tickets) }}">
        <input type="hidden" name="amount" value="{{ $amount }}">
        <div class="form-group">
            <label for="phone_number">Orange Money Phone Number</label>
            <input type="tel" class="form-control" id="phone_number" name="phone_number" pattern="^(\+2376\d{8})|(6\d{8})$" required>
        </div>
        <div class="form-group">
            <label for="transaction_id">Transaction ID</label>
            <input type="text" class="form-control" id="transaction_id" name="transaction_id" required>
            <small>Enter the transaction ID provided by Orange Money after initiating the payment.</small>
        </div>
        <button type="submit" class="btn btn-primary">Pay with Orange Money</button>
    </form>
</div>
@endsection
