@extends('layouts.app')

@section('title', 'Company Cards')

@section('content')
<div class="container">
    <h2>Company Cards</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('company-cards.create') }}" class="btn btn-primary mb-3">Add Company Card</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Card Number</th>
                <th>Card Holder</th>
                <th>Bank Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companyCards as $card)
            <tr>
                <td>{{ $card->card_number }}</td>
                <td>{{ $card->card_holder_name }}</td>
                <td>{{ $card->bank_name }}</td>
                <td>
                    <span class="badge {{ $card->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($card->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('company-cards.edit', $card->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('company-cards.destroy', $card->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
