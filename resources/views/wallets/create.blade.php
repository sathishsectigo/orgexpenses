@extends('layouts.app')
@section('content')

<h2>Add Funds to Wallet</h2>

<form method="POST" action="{{ route('wallet.add') }}">
    @csrf
    <input type="number" name="amount" placeholder="Enter amount" required>
    <input type="text" name="description" placeholder="Description (optional)">
    <button type="submit">Add Funds</button>
</form>

@endsection
