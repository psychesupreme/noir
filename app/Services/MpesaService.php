<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class MpesaService
{
    protected string $baseUrl;
    protected string $consumerKey;
    protected string $consumerSecret;
    protected string $shortcode;
    protected string $passkey;
    protected string $callbackUrl;

    public function __construct()
    {
        $this->consumerKey    = config('services.mpesa.key');
        $this->consumerSecret = config('services.mpesa.secret');
        $this->shortcode      = config('services.mpesa.shortcode');
        $this->passkey        = config('services.mpesa.passkey');
        $this->callbackUrl    = config('services.mpesa.callback_url');

        $this->baseUrl = config('services.mpesa.environment') === 'sandbox'
            ? 'https://sandbox.safaricom.co.ke'
            : 'https://api.safaricom.co.ke';
    }

    /**
     * Generate the mandatory OAuth Access Token from Daraja.
     */
    public function getAccessToken(): string
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

        if ($response->failed()) {
            Log::error('M-Pesa Access Token Generation Failed', ['response' => $response->body()]);
            throw new Exception('Failed to generate M-Pesa token. Check credentials.');
        }

        return $response->json()['access_token'];
    }

    /**
     * Initiate a live Lipa Na M-Pesa Online STK Push request.
     */
    public function sendStkPush(string $phone, int $amount, string $reference): array
    {
        $token = $this->getAccessToken();
        $timestamp = now()->format('YmdHis');
        
        // Secure password generation string mapping required by Safaricom
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        $formattedPhone = $this->formatPhoneNumber($phone);

        $payload = [
            'BusinessShortCode' => $this->shortcode,
            'Password'          => $password,
            'Timestamp'         => $timestamp,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $formattedPhone,
            'PartyB'            => $this->shortcode,
            'PhoneNumber'       => $formattedPhone,
            'CallBackURL'       => $this->callbackUrl,
            'AccountReference'  => $reference,
            'TransactionDesc'   => 'Luxury Atelier'
        ];

        $response = Http::withToken($token)
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", $payload);

        if ($response->failed()) {
            Log::error('M-Pesa STK Push Request Failed', ['payload' => $payload, 'error' => $response->body()]);
            throw new Exception('Safaricom rejected the STK generation instance.');
        }

        return $response->json();
    }

    /**
     * Clean and format Kenyan telephone identifiers into the strict standard 2547XXXXXXXX layout.
     */
    protected function formatPhoneNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        if (str_starts_with($cleaned, '0')) {
            return '254' . substr($cleaned, 1);
        }
        if (str_starts_with($cleaned, '7') || str_starts_with($cleaned, '1')) {
            return '254' . $cleaned;
        }
        if (str_starts_with($cleaned, '254')) {
            return $cleaned;
        }
        
        return $cleaned;
    }
}