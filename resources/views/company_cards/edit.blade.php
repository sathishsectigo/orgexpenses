@extends('layouts.app')

@section('title', 'Edit Company Card')

@section('content')
<div class="container">
    <h2>Edit Company Card</h2>

    <form action="{{ route('company-cards.update', $companyCard->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" name="card_number" class="form-control" value="{{ $companyCard->card_number }}" disabled>
        </div>

        <div class="mb-3">
            <label for="card_holder_name" class="form-label">Card Holder Name</label>
            <input type="text" name="card_holder_name" class="form-control" value="{{ $companyCard->card_holder_name }}" required>
        </div>

        <div class="mb-3">
            <label for="bank_name" class="form-label">Bank Name</label>
            <input type="text" name="bank_name" class="form-control" value="{{ $companyCard->bank_name }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="active" {{ $companyCard->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $companyCard->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('company-cards.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
