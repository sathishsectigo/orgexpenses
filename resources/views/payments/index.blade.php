@extends('layouts.app')

@section('content')
<h2>Payout Processing</h2>

@if($payments->isEmpty())
    <p>No payments recorded.</p>
@else
    <div class="row">
        @foreach($payments as $payment)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $payment->expense->project->name }}</h5>
                        <p><strong>Submitter:</strong> {{ $payment->expense->user->name }}</p>
                        <p><strong>Amount:</strong> ₹{{ number_format($payment->amount, 2) }}</p>
                        <p><strong>Payment Mode:</strong> {{ ucfirst($payment->payment_mode) }}</p>
                        <p><strong>Date:</strong> {{ $payment->payment_date }}</p>
                        @if($payment->payment_receipt)
                            <p>
                                <a href="{{ asset('storage/' . $payment->payment_receipt) }}" target="_blank" class="btn btn-sm btn-primary">
                                    View Receipt
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
