<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 3)->unique();
            $table->string('country')->nullable();
            $table->decimal('exchange_rate', 10, 4)->default(1.0); // INR Default
            $table->timestamps();
        });

        // Insert all major currencies with their respective countries
        DB::table('currencies')->insert([
            ['currency_code' => 'INR', 'country' => 'India', 'exchange_rate' => 1.00],
            ['currency_code' => 'USD', 'country' => 'United States', 'exchange_rate' => 83.00],
            ['currency_code' => 'EUR', 'country' => 'European Union', 'exchange_rate' => 90.00],
            ['currency_code' => 'GBP', 'country' => 'United Kingdom', 'exchange_rate' => 104.00],
            ['currency_code' => 'AUD', 'country' => 'Australia', 'exchange_rate' => 55.00],
            ['currency_code' => 'CAD', 'country' => 'Canada', 'exchange_rate' => 60.00],
            ['currency_code' => 'SGD', 'country' => 'Singapore', 'exchange_rate' => 62.00],
            ['currency_code' => 'JPY', 'country' => 'Japan', 'exchange_rate' => 0.56],
            ['currency_code' => 'CNY', 'country' => 'China', 'exchange_rate' => 11.50],
            ['currency_code' => 'CHF', 'country' => 'Switzerland', 'exchange_rate' => 95.00],
            ['currency_code' => 'NZD', 'country' => 'New Zealand', 'exchange_rate' => 50.00],
            ['currency_code' => 'HKD', 'country' => 'Hong Kong', 'exchange_rate' => 10.50],
            ['currency_code' => 'AED', 'country' => 'United Arab Emirates', 'exchange_rate' => 22.50],
            ['currency_code' => 'SAR', 'country' => 'Saudi Arabia', 'exchange_rate' => 22.20],
            ['currency_code' => 'MYR', 'country' => 'Malaysia', 'exchange_rate' => 18.00],
            ['currency_code' => 'THB', 'country' => 'Thailand', 'exchange_rate' => 2.30],
            ['currency_code' => 'KRW', 'country' => 'South Korea', 'exchange_rate' => 0.062],
            ['currency_code' => 'IDR', 'country' => 'Indonesia', 'exchange_rate' => 0.0055],
            ['currency_code' => 'ZAR', 'country' => 'South Africa', 'exchange_rate' => 4.50],
            ['currency_code' => 'BRL', 'country' => 'Brazil', 'exchange_rate' => 16.50],
            ['currency_code' => 'RUB', 'country' => 'Russia', 'exchange_rate' => 0.90],
            ['currency_code' => 'EGP', 'country' => 'Egypt', 'exchange_rate' => 3.80],
            ['currency_code' => 'PKR', 'country' => 'Pakistan', 'exchange_rate' => 0.29],
            ['currency_code' => 'BDT', 'country' => 'Bangladesh', 'exchange_rate' => 0.78],
            ['currency_code' => 'VND', 'country' => 'Vietnam', 'exchange_rate' => 0.0035],
            ['currency_code' => 'PHP', 'country' => 'Philippines', 'exchange_rate' => 1.50],
            ['currency_code' => 'NGN', 'country' => 'Nigeria', 'exchange_rate' => 0.18],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
