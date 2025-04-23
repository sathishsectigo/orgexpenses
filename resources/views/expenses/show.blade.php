@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Expense Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Expense Information -->
                <div class="col-md-6">
                    <p><strong>Amount:</strong> {{ number_format($expense->amount, 2) }}</p>
                    <p><strong>Description:</strong> {{ $expense->description }}</p>
                    <p><strong>Project:</strong> {{ $expense->project->name ?? 'N/A' }}</p>
                    <p><strong>Type:</strong> {{ ucfirst($expense->type) }}</p>
                    <p><strong>Mode of Payment:</strong> {{ ucfirst($expense->mode) }}</p>
                </div>

                <!-- Approval & Status Information -->
                <div class="col-md-6">
                    <p><strong>Submitted By:</strong> {{ $expense->submittedBy->name }}</p>
                    <p><strong>Submitted To:</strong> {{ $expense->submittedTo->name ?? 'N/A' }}</p>
                    <p><strong>Entry Date:</strong> {{ $expense->created_at->format('d M Y, H:i A') }}</p>
                    <p><strong>Approval Date:</strong> 
                        {{ $expense->approved_date ? $expense->approved_date->format('d M Y, H:i A') : 'Pending' }}
                    </p>

                    <p><strong>Status:</strong> 
                        @if ($expense->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif ($expense->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </p>

                    <!-- Show rejection reason if the expense is rejected -->
                    @if ($expense->status == 'rejected')
                        <p><strong>Rejection Reason:</strong> {{ $expense->rejection_reason }}</p>
                    @endif
                </div>
            </div>

            <!-- Payment Attachment -->
            @if ($expense->payment_attachment)
                <hr>
                <h5>Payment Attachment:</h5>
                <a href="{{ asset('storage/' . $expense->payment_attachment) }}" target="_blank">
                    <img src="{{ asset('storage/' . $expense->payment_attachment) }}" class="img-fluid" alt="Attachment" width="200">
                </a>
            @endif
        </div>

        <div class="card-footer">
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
