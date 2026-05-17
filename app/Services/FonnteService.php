<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected ?string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    public function send(string $phone, string $message): bool
    {
        if (!$this->token) return false;

        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->post('https://api.fonnte.com/send', [
            'target'  => $phone,
            'message' => $message,
        ]);

        return $response->successful();
    }
}
