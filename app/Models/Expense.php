<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model {
    use HasFactory;
    protected $fillable = [
        'project_id',
        'submitted_by',
        'amount',
        'description',
        'type',
        'mode',
        'status',
        'rejection_reason',
        'linked_expense_id',
        'payment_attachment',
        'invoice_attachment',
        'expense_date', 
        'approved_by', 
        'approved_date', 
        'complete_date'
    ];

     /**
     * Get the project associated with the expense.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who submitted the expense.
     */
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    /**
     * Get the linked expense (Parent).
     */
    public function linkedExpense()
    {
        return $this->belongsTo(Expense::class, 'linked_expense_id');
    }

    /**
     * Get the expenses that are linked to this expense (Children).
     */
    public function linkedExpenses()
    {
        return $this->hasMany(Expense::class, 'linked_expense_id');
    }

    public function companyCard()
    {
        return $this->belongsTo(CompanyCard::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    // Check if the expense is company-paid (no further action required)
    public function isCompanyPaid() {
        return in_array($this->mode, ['petty_cash', 'company_card']);
    }

    public function isPaid() {
        return $this->payments()->exists();
    }
}
