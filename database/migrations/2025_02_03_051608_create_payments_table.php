<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_id')->constrained('expenses')->onDelete('cascade'); // Payment linked to an expense
            $table->foreignId('paid_by')->constrained('users')->onDelete('cascade'); // Who made the payment
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_mode', ['cash', 'company_card', 'personal_card', 'personal_upi', 'petty_cash']); // Payment mode
            $table->string('payment_receipt')->nullable(); // Proof of payment
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
