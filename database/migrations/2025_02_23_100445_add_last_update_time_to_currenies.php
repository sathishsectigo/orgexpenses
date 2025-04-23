<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->timestamp('last_updated_at')->nullable()->after('exchange_rate');
        });
    }

    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn('last_updated_at');
        });
    }
};

