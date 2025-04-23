<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('currency', 3)->default('INR')->after('amount');
            $table->decimal('converted_inr_amount', 15, 2)->nullable()->after('currency');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['currency', 'converted_inr_amount']);
        });
    }
};
