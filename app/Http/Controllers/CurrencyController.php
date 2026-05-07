<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    public function getRates()
    {
        try {
            $rates = Cache::remember('currency_rates', 3600, function () {
                $apiKey = config('services.exchangerate.key');
                $response = Http::get("https://v6.exchangerate-api.com/v6/c9505cbf8cdfbb817e3f1114/latest/IDR");

                if ($response->successful()) {
                    return $response->json()['conversion_rates'];
                }

                throw new \Exception('Gagal fetch kurs');
            });

            $currencies = ['USD', 'EUR', 'SGD', 'AUD', 'GBP', 'JPY', 'MYR', 'CNY', 'SAR'];
            $filtered = [];

            foreach ($currencies as $currency) {
                if (isset($rates[$currency])) {
                    $filtered[$currency] = round(1 / $rates[$currency], 2);
                }
            }

            return response()->json([
                'success' => true,
                'rates' => $filtered,
                'base' => 'IDR',
                'updated_at' => now()->format('H:i'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'rates' => [
                    'USD' => 16000,
                    'EUR' => 17500,
                    'SGD' => 12000,
                    'AUD' => 10500,
                    'GBP' => 20000,
                    'JPY' => 110,
                    'MYR' => 3500,
                    'CNY' => 2200,
                    'SAR' => 4200,
                ],
                'base' => 'IDR',
                'updated_at' => 'offline',
                'message' => 'Menggunakan kurs offline',
            ]);
        }
    }
}
