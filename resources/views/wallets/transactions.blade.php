@extends('layouts.app')
@section('content')

<h2>Wallet Transactions</h2>

<table>
    <tr>
        <th>Amount</th>
        <th>Type</th>
        <th>Description</th>
        <th>Date</th>
    </tr>
    @foreach($transactions as $transaction)
    <tr>
        <td>{{ $transaction->amount }}</td>
        <td>{{ ucfirst($transaction->type) }}</td>
        <td>{{ $transaction->description }}</td>
        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
    </tr>
    @endforeach
</table>

@endsection
