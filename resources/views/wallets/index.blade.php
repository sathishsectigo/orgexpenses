@extends('layouts.app')

@section('title', 'Wallet')

@section('content')
<div class="container">
    <h2 class="mb-4">Wallet</h2>

    <!-- Wallet Balance Card -->
    <div class="card bg-light mb-4">
        <div class="card-body text-center">
            <h5 class="card-title">Available Balance</h5>
            <h2 class="fw-bold text-success">₹{{ number_format($wallet->balance, 2) }}</h2>
            <a href="{{ route('wallet.create') }}" class="btn btn-primary mt-2">Add Funds</a>
        </div>
    </div>

    <!-- Transactions Section -->
    <h4>Transaction History</h4>

    @if($transactions->isEmpty())
        <p class="text-muted">No transactions found.</p>
    @else
        <div class="row">
            @foreach($transactions as $transaction)
                <div class="col-md-6">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">
                                {{ $transaction->created_at->format('d M Y, H:i A') }}
                            </h6>

                            <h5 class="{{ $transaction->type == 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->type == 'credit' ? '+' : '-' }} ₹{{ number_format($transaction->amount, 2) }}
                            </h5>

                            <p class="card-text">{{ $transaction->description }}</p>

                            <!-- Transaction Type Badge -->
                            <span class="badge {{ $transaction->type == 'credit' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>

                            <!-- Remove Delete Button -->
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
