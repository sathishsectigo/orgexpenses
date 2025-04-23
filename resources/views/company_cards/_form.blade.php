<div class="mb-3">
    <label for="card_number" class="form-label">Card Number</label>
    <input type="text" name="card_number" class="form-control" value="{{ old('card_number', $companyCard->card_number ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="card_holder_name" class="form-label">Card Holder Name</label>
    <input type="text" name="card_holder_name" class="form-control" value="{{ old('card_holder_name', $companyCard->card_holder_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="bank_name" class="form-label">Bank Name</label>
    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $companyCard->bank_name ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" class="form-control">
        <option value="active" {{ (isset($companyCard) && $companyCard->status === 'active') ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ (isset($companyCard) && $companyCard->status === 'inactive') ? 'selected' : '' }}>Inactive</option>
    </select>
</div>
