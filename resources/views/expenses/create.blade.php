@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Expense</h2>
    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Project Selection -->
        <div class="mb-3">
            <label for="project_id" class="form-label">Project</label>
            <select class="form-control" name="project_id" id="project_id" required>
                <option value="">Select Project</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }} (Approver: {{ $project->reportingInCharge->name ?? 'N/A' }})</option>
                @endforeach
            </select>
        </div>

        <!-- Amount -->
        @php
            $currencies = \App\Models\Currency::all();
        @endphp
        <div class="mb-3 row">
            <label for="amount" class="col-sm-2 col-form-label">Amount</label>
            <div class="col-sm-3">
                <select name="currency" id="currency" class="form-select">
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->currency_code }}">
                            {{ $currency->currency_code }} - {{ $currency->country }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4">
                <input type="number" name="amount" id="amount" class="form-control" placeholder="Enter Amount" required>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
        </div>

        <!-- Type -->
        <div class="mb-3">
            <label for="type" class="form-label">Expense Type</label>
            <select class="form-control" name="type" id="type" required>
                <option value="advance">Advance</option>
                <option value="part_payment">Part Payment</option>
                <option value="complete_payment">Complete Payment</option>
            </select>
        </div>

        <!-- Linked Expense (Only for Part Payment and Advance) -->
        <div class="mb-3" id="linked-expense-group">
            <label for="linked_expense_id" class="form-label">Linked Expense</label>
            <select class="form-control" name="linked_expense_id" id="linked_expense_id">
                <option value="">None</option>

                <optgroup label="Advance Payments">
                    @foreach ($unlinkedAdvances as $advance)
                        <option value="{{ $advance->id }}">Advance: ₹{{ $advance->amount }} ({{ $advance->project->name }} - {{ $advance->expense_date }})</option>
                    @endforeach
                </optgroup>

                <optgroup label="Part Payments">
                    @foreach ($unlinkedPartPayments as $partPayment)
                        <option value="{{ $partPayment->id }}">Part Payment: ₹{{ $partPayment->amount }} ({{ $advance->project->name }} - {{ $partPayment->expense_date }})</option>
                    @endforeach
                </optgroup>
            </select>
        </div>

        <!-- Payment Mode -->
        <div class="mb-3">
            <label for="mode" class="form-label">Payment Mode</label>
            <select class="form-control" name="mode" id="mode" required>
                <option value="cash">Cash</option>
                <option value="company_card">Company Card</option>
                <option value="personal_card">Personal Card</option>
                <option value="personal_upi">Personal UPI</option>
                <option value="petty_cash">Petty Cash</option>
            </select>
        </div>

        <div class="mb-3" id="company_card_dropdown" style="display: none;">
            <label for="company_card_id" class="form-label">Company Card</label>
            <select name="company_card_id" id="company_card_id" class="form-control">
                <option value="">-- Select Company Card --</option>
                @foreach ($companyCards as $card)
                    <option value="{{ $card->id }}">{{ $card->card_number }} - {{ $card->bank_name }}</option>
                @endforeach
            </select>
        </div>

        <script>
        document.getElementById('mode').addEventListener('change', function() {
            if (this.value === 'company_card') {
                document.getElementById('company_card_dropdown').style.display = 'block';
            } else {
                document.getElementById('company_card_dropdown').style.display = 'none';
            }
        });
        </script>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label for="invoice_attachment" class="form-label">Invoice OR Receipt Attachment </label>
            <input type="file" class="form-control" id="invoice_attachment" name="invoice_attachment" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>


        <!-- File Upload (Payment Attachment) -->
        <div class="mb-3">
            <label for="payment_attachment" class="form-label">Payment Attachment (Optional)</label>
            <input type="file" class="form-control" name="payment_attachment" id="payment_attachment" accept=".jpg,.jpeg,.png,.pdf">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Expense</button>
    </form>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let expenseType = document.getElementById("type");
        let linkedExpenseGroup = document.getElementById("linked-expense-group");

        function toggleLinkedExpense() {
            if (expenseType.value === "advance") {
                linkedExpenseGroup.style.display = "none";
            } else {
                linkedExpenseGroup.style.display = "block";
            }
        }

        expenseType.addEventListener("change", toggleLinkedExpense);
        toggleLinkedExpense(); // Initial check
    });
    </script>

</div>
@endsection

