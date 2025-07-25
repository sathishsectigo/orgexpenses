<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('expense_date');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->date('expense_date')->nullable()->after('amount');
        });
    }
};
