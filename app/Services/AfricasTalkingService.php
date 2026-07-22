<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AfricasTalkingService
{
    protected string $username;
    protected ?string $apiKey;
    protected ?string $from;
    protected string $baseUrl;

    public function __construct()
    {
        $this->username = config('services.africastalking.username', 'sandbox');
        $this->apiKey = config('services.africastalking.api_key');
        $this->from = config('services.africastalking.from');

        $this->baseUrl = $this->username === 'sandbox'
            ? 'https://api.sandbox.africastalking.com/version1/messaging'
            : 'https://api.africastalking.com/version1/messaging';
    }

    /**
     * Send an SMS message to a phone number.
     *
     * @param string $to Phone number (e.g. 0712345678 or +254712345678)
     * @param string $message Text content of the SMS
     * @return array Responded status and payload
     */
    public function sendSms(string $to, string $message): array
    {
        $formattedPhone = $this->formatPhoneNumber($to);
        if (!$formattedPhone) {
            Log::warning("AfricasTalking SMS failed: Invalid phone number [{$to}]");
            return ['success' => false, 'error' => 'Invalid phone number'];
        }

        if (empty($this->apiKey)) {
            Log::info("[DEV MOCK SMS] To: {$formattedPhone} | Message: {$message}");
            return [
                'success' => true,
                'mock' => true,
                'message' => 'SMS logged to dev channel (No API Key configured)',
            ];
        }

        try {
            $postData = [
                'username' => $this->username,
                'to'       => $formattedPhone,
                'message'  => $message,
            ];

            if ($this->from) {
                $postData['from'] = $this->from;
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'apiKey' => $this->apiKey,
            ])->asForm()->post($this->baseUrl, $postData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info("AfricasTalking SMS Sent Successfully to {$formattedPhone}", $data ?? []);
                return ['success' => true, 'data' => $data];
            }

            Log::error("AfricasTalking SMS API Error", [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [
                'success' => false,
                'status'  => $response->status(),
                'error'   => $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error("AfricasTalking SMS Exception: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Format Kenyan phone numbers into international E.164 format (+254...).
     */
    protected function formatPhoneNumber(string $phone): ?string
    {
        $cleaned = preg_replace('/[^\d+]/', '', trim($phone));

        if (str_starts_with($cleaned, '+254') && strlen($cleaned) === 13) {
            return $cleaned;
        }

        if (str_starts_with($cleaned, '254') && strlen($cleaned) === 12) {
            return '+' . $cleaned;
        }

        if (str_starts_with($cleaned, '07') || str_starts_with($cleaned, '01')) {
            if (strlen($cleaned) === 10) {
                return '+254' . substr($cleaned, 1);
            }
        }

        if (str_starts_with($cleaned, '+')) {
            return $cleaned;
        }

        return null;
    }
}
