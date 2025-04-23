<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Project;
use App\Models\User;
use App\Models\CompanyCard;
use App\Models\Currency;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('submittedBy', 'project', 'linkedExpense')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $projects = Project::all();

        $companyCards = CompanyCard::active()->get();
        // Fetch advance expenses that are not linked to any part or complete payment
        // Get unlinked advances with their project names
        $unlinkedAdvances = Expense::where('type', 'advance')
            ->whereDoesntHave('linkedExpenses')
            ->with('project:id,name')
            ->get(['id', 'amount', 'description', 'project_id']);

        // Get unlinked part payments with their project names
        $unlinkedPartPayments = Expense::where('type', 'part_payment')
            ->whereDoesntHave('linkedExpenses')
            ->with('project:id,name')
            ->get(['id', 'amount', 'description', 'project_id']);

        return view('expenses.create', compact('projects', 'companyCards', 'unlinkedAdvances', 'unlinkedPartPayments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|max:3',
            'type' => 'required|in:advance,part_payment,complete_payment',
            'description' => 'required|string',
            'mode' => 'required|in:cash,company_card,personal_card,personal_upi,petty_cash',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payment_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'linked_expense_id' => 'nullable|exists:expenses,id'
        ],[
            'project_id.required' => 'Please select a project.',
            'project_id.exists' => 'The selected project is invalid.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Amount must be at least 1.',
            'description.required' => 'Please provide a description.',
            'type.required' => 'Please select a payment type.',
            'type.in' => 'Invalid payment type selected.',
            'mode.required' => 'Please select a payment mode.',
            'mode.in' => 'Invalid payment mode selected.',
            'invoice_attachment.mimes' => 'Attachment must be a JPG, PNG, or PDF file.',
            'invoice_attachment.max' => 'Attachment size should not exceed 2MB.',
            'payment_attachment.mimes' => 'Attachment must be a JPG, PNG, or PDF file.',
            'payment_attachment.max' => 'Attachment size should not exceed 2MB.'
        ]);


        $currencyCode = strtoupper($request->currency);
        if($currencyCode == 'INR') {
            $exchangeRate = 1;
        } else {
            $exchangeRate = Currency::getExchangeRate($currencyCode);
        }
        if (!$exchangeRate) {
            return back()->withErrors(['currency' => 'Invalid currency selected.']);
        }

        $convertedAmount = $request->amount * $exchangeRate;  
        $expense = new Expense();
        $expense->submitted_by = Auth::id();
        $expense->project_id = $request->project_id;
        $expense->amount = $request->amount;
        $expense->type = $request->type;
        $expense->description = $request->description;
        $expense->mode = $request->mode;
        $expense->linked_expense_id = $request->linked_expense_id;
        $project = Project::find($request->project_id);
        if ($project && $project->reporting_manager_id == Auth::id()) {
            $expense->status = 'approved'; // Auto-approve
        } else {
            $expense->status = 'pending';
        }
        $expense->currency = $request->currency;
        $expense->converted_inr_amount = $convertedAmount;

        if ($request->hasFile('payment_attachment')) {
            $filePath = $request->file('payment_attachment')->store('expenses', 'public');
            $expense->payment_attachment = $filePath;
        }
        $invoicePath = $request->file('invoice_attachment')->store('invoices', 'public');
        $expense->invoice_attachment = $invoicePath;

        $expense->save();

        return redirect()->route('expenses.index')->with('success', 'Expense submitted successfully.');
    }

    public function submitExpense(Request $request)
    {
        $user = auth()->user(); // Get authenticated user

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
            'project' => 'required|string',
            'amount' => 'required|numeric',
            'paymentMode' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'required|string',
        ]);

        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('expenses', 'public');
        }
        $expense = new Expense();
        $expense->create([
            'project' => $data['project'],
            'amount' => $data['amount'],
            'payment_mode' => $data['paymentMode'],
            'description' => $data['description'],
            'image_path' => $imagePath
        ]);

        return response()->json(['success' => true, 'message' => 'Expense submitted successfully']);
    }

    public function show(Expense $expense)
    {
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->status != 'pending') {
            return redirect()->route('expenses.index')->with('error', 'Cannot edit an approved expense.');
        }

        $projects = Project::all();
        $approvers = User::where('role', 'manager')->get();
        return view('expenses.edit', compact('expense', 'projects', 'approvers'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->status != 'pending') {
            return redirect()->route('expenses.index')->with('error', 'Cannot edit an approved expense.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:advance,part_payment,complete_payment',
            'mode' => 'required|in:cash,company_card,personal_card,personal_upi,petty_cash',
            'project_id' => 'nullable|exists:projects,id',
            'submitted_to' => 'required|exists:users,id',
            'payment_attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $expense->update($request->all());

        if ($request->hasFile('payment_attachment')) {
            $filePath = $request->file('payment_attachment')->store('expenses');
            $expense->payment_attachment = $filePath;
        }

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->status != 'pending') {
            return redirect()->route('expenses.index')->with('error', 'Cannot delete an approved expense.');
        }

        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }

    public function approve(Expense $expense)
    {
        if ($expense->status == 'pending') {
            $expense->update(['status' => 'approved', 'approved_date' => now()]);
            return redirect()->route('expenses.index')->with('success', 'Expense approved.');
        }
        return redirect()->route('expenses.index')->with('error', 'Expense cannot be approved.');
    }

    public function reject(Request $request, Expense $expense)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        if ($expense->status === 'pending') {
            $expense->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason
            ]);

            return redirect()->route('expenses.index')->with('error', 'Expense has been rejected.');
        }

        return redirect()->route('expenses.index')->with('error', 'Expense cannot be rejected.');
    }

    // 🔹 Show approved expenses waiting for payout (excluding company-paid expenses)
    public function payoutList() {
        $expenses = Expense::where('status', 'approved')
            ->where('is_paid', false)
            ->whereNotIn('mode', ['petty_cash', 'company_card'])
            ->with('user', 'project')
            ->get();

        return view('expenses.payout_list', compact('expenses'));
    }

    // 🔹 Mark an expense as paid
    public function markAsPaid(Request $request, Expense $expense) {
        $request->validate([
            'payout_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('payout_attachment')) {
            $filePath = $request->file('payout_attachment')->store('payouts', 'public');
            $expense->payout_attachment = $filePath;
        }

        $expense->is_paid = true;
        $expense->save();

        return redirect()->route('expenses.payout_list')->with('success', 'Expense marked as paid.');
    }

}
