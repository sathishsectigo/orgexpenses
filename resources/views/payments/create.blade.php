@extends('layouts.app')

@section('content')
<h2>Record Payment</h2>

<form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="expense_id">Expense:</label>
    <select name="expense_id" class="form-control mb-2">
        @foreach($pendingExpenses as $expense)
            <option value="{{ $expense->id }}">{{ $expense->description }} - ₹{{ number_format($expense->amount, 2) }}</option>
        @endforeach
    </select>

    <label for="amount">Amount:</label>
    <input type="number" name="amount" class="form-control mb-2" required>
    
    <label for="payment_mode">Payment Mode:</label>
    <select name="payment_mode" class="form-control mb-2">
        <option value="cash">Cash</option>
        <option value="company_card">Company Card</option>
        <option value="personal_card">Personal Card</option>
        <option value="personal_upi">Personal UPI</option>
    </select>

    <label for="payment_receipt">Upload Receipt (Optional):</label>
    <input type="file" name="payment_receipt" class="form-control mb-2">

    <button type="submit" class="btn btn-success">Submit Payment</button>
</form>
@endsection
