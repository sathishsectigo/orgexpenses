<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    use HasFactory;
    protected $fillable = ['expense_id', 'amount', 'payment_date', 'is_final'];

    public function expense() {
        return $this->belongsTo(Expense::class);
    }
}
