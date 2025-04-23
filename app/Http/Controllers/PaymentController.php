<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index() {
        $payments = Payment::where('paid_by', Auth::id())->with('expense')->get();
        return view('payments.index', compact('payments'));
    }

    public function create() {
        $pendingExpenses = Expense::where('status', 'approved')
            ->whereDoesntHave('payments')
            ->whereNotIn('mode', ['petty_cash', 'company_card']) // Exclude company-paid expenses
            ->get();

        return view('payments.create', compact('pendingExpenses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_id' => 'required|exists:expenses,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|in:cash,company_card,personal_card,personal_upi,petty_cash',
            'payment_receipt' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        $expense = Expense::findOrFail($request->expense_id);

        if ($expense->isPaid()) {
            return redirect()->route('payments.index')->with('error', 'This expense is already paid.');
        }

        $payment = new Payment();
        $payment->expense_id = $request->expense_id;
        $payment->paid_by = Auth::id();
        $payment->amount = $request->amount;
        $payment->payment_date = now();
        $payment->payment_mode = $request->payment_mode;

        if ($request->hasFile('payment_receipt')) {
            $filePath = $request->file('payment_receipt')->store('payments');
            $payment->payment_receipt = $filePath;
        }

        $payment->save();

        $expense = Expense::find($request->expense_id);
        $expense->update(['status' => 'paid', 'complete_date' => now()]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('payments.show', compact('payment'));
    }

}

