<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Protect the dashboard with authentication middleware
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();

        // Get expenses submitted by the user that are pending approval
        $submittedExpenses = Expense::where('submitted_by', $userId)
                                    ->where('status', 'pending')
                                    ->with(['project'])
                                    ->get();

        // Get expenses waiting for the logged-in user's approval
        $approvalsPending = Expense::whereHas('project', function ($query) use ($userId) {
            $query->where('reporting_manager_id', $userId);
        })->where('status', 'pending')->with(['submittedBy', 'project'])->get();
        
        // Fetch data for logged-in user
        $approvedUnpaid = Expense::where('submitted_by', $userId)
            ->where('status', 'approved')
            ->whereDoesntHave('payments')
            ->sum('amount');

        $unapproved = Expense::where('submitted_by', $userId)
            ->where('status', 'pending')
            ->sum('amount');

        $totalRequested = $approvedUnpaid + $unapproved;

        $overallData = null;
        $user = auth()->user();
        $hasManageAccounts = $user->hasPermission('manage-accounts');
        if ($hasManageAccounts) {
            $overallApprovedUnpaid = Expense::where('status', 'approved')
                ->whereDoesntHave('payments')
                ->sum('amount');

            $overallUnapproved = Expense::where('status', 'pending')
                ->sum('amount');

            $overallTotalRequested = $overallApprovedUnpaid + $overallUnapproved;

            $overallData = [
                'approvedUnpaid' => $overallApprovedUnpaid,
                'unapproved' => $overallUnapproved,
                'totalRequested' => $overallTotalRequested,
            ];
        }
        return view('dashboard', compact('submittedExpenses', 'approvalsPending', 'approvedUnpaid', 'unapproved', 'totalRequested', 'overallData'), [
            'pageTitle' => 'Dashboard',
        ]);
    }
}
