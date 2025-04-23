@extends('layouts.app')

@section('content')
<h2>Payout Processing</h2>

@if($expenses->isEmpty())
    <p>No expenses pending for payout.</p>
@else
    <div class="row">
        @foreach($expenses as $expense)
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $expense->project->name }}</h5>
                        <p><strong>Submitter:</strong> {{ $expense->user->name }}</p>
                        <p><strong>Amount:</strong> ₹{{ number_format($expense->amount, 2) }} ({{ $expense->currency }})</p>
                        <p><strong>Payment Mode:</strong> {{ ucfirst($expense->mode) }}</p>
                        <p>
                            <a href="{{ asset('storage/' . $expense->invoice_attachment) }}" target="_blank" class="btn btn-sm btn-primary">
                                View Invoice
                            </a>
                        </p>
                        <form action="{{ route('expenses.mark_paid', $expense->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label>Upload Payout Proof (Optional):</label>
                            <input type="file" name="payout_attachment" class="form-control mb-2">
                            <button type="submit" class="btn btn-success btn-sm">Mark as Paid</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
