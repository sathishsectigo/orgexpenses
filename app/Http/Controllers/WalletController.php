<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display wallet balance & transactions.
     */
    public function index()
    {
        $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
        $transactions = $wallet->transactions()->latest()->get();

        return view('wallets.index', compact('wallet', 'transactions'));
    }

    /**
     * Show the form for adding funds.
     */
    public function create()
    {
        return view('wallets.create');
    }

    /**
     * Add funds to wallet.
     */
    public function addFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->firstOrFail();

        // Increase wallet balance
        $wallet->increment('balance', $request->amount);

        // Log transaction
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'type' => 'credit',
            'description' => $request->description ?? 'Added funds'
        ]);

        return redirect()->route('wallet.index')->with('success', 'Funds added successfully');
    }

    /**
     * Deduct funds from wallet (for expense payments).
     */
    public function deductFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->firstOrFail();

        if ($wallet->balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }

        // Deduct wallet balance
        $wallet->decrement('balance', $request->amount);

        // Log transaction
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'type' => 'debit',
            'description' => $request->description ?? 'Expense payment'
        ]);

        return redirect()->route('wallet.index')->with('success', 'Payment deducted from wallet');
    }

    /**
     * Show wallet transactions.
     */
    public function transactions()
    {
        $wallet = Wallet::where('user_id', Auth::id())->firstOrFail();
        $transactions = $wallet->transactions()->latest()->get();

        return view('wallets.transactions', compact('transactions'));
    }

    /**
     * Delete a transaction (if allowed).
     */
    public function destroy(WalletTransaction $transaction)
    {
        $wallet = Wallet::where('user_id', Auth::id())->firstOrFail();

        if ($transaction->wallet_id !== $wallet->id) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        if ($transaction->type === 'debit') {
            // Restore balance on deletion of a debit transaction
            $wallet->increment('balance', $transaction->amount);
        }

        $transaction->delete();
        return redirect()->route('wallet.transactions')->with('success', 'Transaction deleted');
    }
}
