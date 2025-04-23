<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['currency_code', 'country', 'exchange_rate', 'last_updated_at'];

    public static function getExchangeRate($currencyCode)
    {
        $currency = self::where('currency_code', strtoupper($currencyCode))->first();

        if (!$currency) {
            return null;
        }

        // If last update was within 6 hours, return stored exchange rate
        if ($currency->last_updated_at && Carbon::parse($currency->last_updated_at)->diffInHours(now()) < 6) {
            return $currency->exchange_rate;
        }

        // Fetch live exchange rate if outdated
        $apiKey = '8f3f96cdaae81d6f2f3419aa'; 
        $response = Http::get("https://v6.exchangerate-api.com/v6/$apiKey/latest/INR");

        if ($response->successful()) {
            $data = $response->json();
            $rates = $data['conversion_rates'] ?? [];

            if (isset($rates[$currencyCode])) {
                $newRate = 1 / $rates[$currencyCode];

                // Update database
                $currency->update([
                    'exchange_rate' => $newRate,
                    'last_updated_at' => now(),
                ]);

                return $newRate;
            }
        }

        // Return stored exchange rate if API fails
        return $currency->exchange_rate;
    }
}
