@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h2>Welcome, {{ auth()->user()->name }}</h2>
@if(isset($overallData))
<!-- Overall Summary for Users with 'manage-accounts' Permission -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5 class="card-title">Overall Approved (Unpaid)</h5>
                <p class="card-text">₹{{ number_format($overallData['approvedUnpaid'], 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title">Overall Unapproved</h5>
                <p class="card-text">₹{{ number_format($overallData['unapproved'], 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info shadow">
            <div class="card-body">
                <h5 class="card-title">Overall Total Requested</h5>
                <p class="card-text">₹{{ number_format($overallData['totalRequested'], 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- User-Specific Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow">
            <div class="card-body">
                <h5 class="card-title">Approved (Unpaid)</h5>
                <p class="card-text">₹{{ number_format($approvedUnpaid, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning shadow">
            <div class="card-body">
                <h5 class="card-title">Unapproved</h5>
                <p class="card-text">₹{{ number_format($unapproved, 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info shadow">
            <div class="card-body">
                <h5 class="card-title">Total Requested Payment</h5>
                <p class="card-text">₹{{ number_format($totalRequested, 2) }}</p>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->hasPermission('manage-accounts'))
<div class="row">
    <!-- Block 1: Pending Approvals Submitted by User -->
    <div class="col-md-6">
        <div class="card">
            <h3 class="mt-4">Payouts Pending</h3>
            @if($payouts->isEmpty())
                <p>No payouts pending.</p>
            @else
                <ul class="list-group">
                    @foreach($payouts as $expense)
                        <li class="list-group-item">
                            <strong>{{ $expense->project->name }}</strong> - ₹{{ number_format($expense->amount, 2) }}
                            <span class="badge bg-warning">{{ ucfirst($expense->mode) }}</span>
                            <a href="{{ asset('storage/' . $expense->invoice_attachment) }}" target="_blank" class="btn btn-sm btn-primary">View Invoice</a>
                            <form action="{{ route('expenses.mark_paid', $expense->id) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                @csrf
                                <input type="file" name="payout_attachment" class="form-control form-control-sm mb-2">
                                <button type="submit" class="btn btn-success btn-sm">Mark as Paid</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endif

<div class="row">
    <!-- Block 1: Pending Approvals Submitted by User -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                Pending Approvals (Submitted by You)
            </div>
            <div class="card-body">
                @if ($submittedExpenses->isEmpty())
                    <p>No pending approvals.</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Amount</th>
                                <th>Payment Mode</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submittedExpenses as $expense)
                                <tr>
                                    <td>{{ $expense->project->name }}</td>
                                    <td>{{ number_format($expense->amount, 2) }} {{ config('app.currency', 'INR') }}</td>
                                    <td>{{ ucfirst($expense->payment_mode) }}</td>
                                    <td>
                                        @if($expense->invoice_attachment)
                                            <a href="{{ asset('storage/invoices/' . $expense->invoice_attachment) }}" target="_blank"
                                               onclick="openPopup('{{ asset('storage/invoices/' . $expense->invoice_attachment) }}'); return false;">
                                                View Invoice
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Block 2: Submissions Waiting for User Approval -->
    @if (!$approvalsPending->isEmpty())
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-info text-white">
                Submissions Waiting for Your Approval
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Submitter</th>
                            <th>Project</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Invoice</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approvalsPending as $expense)
                            <tr>
                                <td>{{ $expense->submittedBy->name }}</td>
                                <td>{{ $expense->project->name }}</td>
                                <td>{{ number_format($expense->amount, 2) }} {{ config('app.currency', 'INR') }}</td>
                                <td>{{ ucfirst($expense->payment_mode) }}</td>
                                <td>
                                    @if($expense->invoice_attachment)
                                        <a href="{{ asset('storage/invoices/' . $expense->invoice_attachment) }}" target="_blank"
                                           onclick="openPopup('{{ asset('storage/invoices/' . $expense->invoice_attachment) }}'); return false;">
                                            View Invoice
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-sm btn-primary">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Pop-up Script for Invoice -->
<script>
    function openPopup(url) {
        window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
    }
</script>
@endsection
