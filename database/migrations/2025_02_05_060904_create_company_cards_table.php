<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('company_cards', function (Blueprint $table) {
            $table->id();
            $table->string('card_number')->unique();
            $table->string('card_holder_name');
            $table->string('bank_name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('company_cards');
    }
};
