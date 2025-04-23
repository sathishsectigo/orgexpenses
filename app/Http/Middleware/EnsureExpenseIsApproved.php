<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureExpenseIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        if (!$request->expense->approved_by) {
            return redirect()->route('expenses.index')->with('error', 'Expense not approved');
        }
        return $next($request);
    }
}
