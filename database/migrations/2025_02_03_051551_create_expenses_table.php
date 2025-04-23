<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade'); // Employee who submitted
            $table->foreignId('submitted_to')->constrained('users')->onDelete('cascade'); // Approving manager
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null'); // Associated project
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->date('expense_date'); // Date of expense
            $table->enum('type', ['advance', 'part_payment', 'complete_payment']); // Type of expense
            $table->enum('mode', ['cash', 'company_card', 'personal_card', 'personal_upi', 'petty_cash']); // Payment mode
            $table->foreignId('linked_expense_id')->nullable()->constrained('expenses')->onDelete('set null'); // Link for Advance → Part → Complete
            $table->string('status')->default('pending'); // pending, approved, paid, closed
            $table->string('payment_attachment')->nullable(); // Invoice or proof of payment
            $table->date('approved_date')->nullable(); 
            $table->date('complete_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};

