@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Expenses</h2>
    <a href="{{ route('expenses.create') }}" class="btn btn-success">Add Expense</a>
    @if(auth()->user()->hasRole('Admin'))
        &nbsp;&nbsp;<a href="{{ route('company-cards.index') }}" class="btn btn-primary">View Company Cards</a>
    @endif
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Description</th>
                <th>Type</th>
                <th>Mode</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->created_at }}</td>
                    <td>{{ $expense->amount }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ ucfirst($expense->type) }}</td>
                    <td>{{ ucfirst($expense->mode) }}</td>
                    <td>{{ ucfirst($expense->status) }}</td>
                    <td>
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this expense?')">Delete</button>
                        </form>
                        @if ($expense->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                        @elseif ($expense->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif ($expense->status == 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
