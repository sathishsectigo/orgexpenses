@extends('layouts.app')

@section('title', 'Add Company Card')

@section('content')
<div class="container">
    <h2>Add Company Card</h2>

    <form action="{{ route('company-cards.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" name="card_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="card_holder_name" class="form-label">Card Holder Name</label>
            <input type="text" name="card_holder_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="bank_name" class="form-label">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" selected>Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('company-cards.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
