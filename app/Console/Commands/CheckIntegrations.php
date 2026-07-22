<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckIntegrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-integrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit external API integration credentials (Resend, Africa\'s Talking, M-Pesa, eTIMS)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('================================================================');
        $this->info('       Noir & Bloom ERP - External API Integration Diagnostic    ');
        $this->info('================================================================');

        $rows = [];

        // 1. Resend Mail Service
        $resendKey = config('services.resend.key');
        $mailer = config('mail.default');
        if (!empty($resendKey) && str_starts_with($resendKey, 're_')) {
            $rows[] = ['Resend Email API', $mailer, '[OK]', 'Valid key (starts with re_)'];
        } elseif (!empty($resendKey)) {
            $rows[] = ['Resend Email API', $mailer, '[WARNING]', 'Key configured but non-standard prefix (' . substr($resendKey, 0, 7) . '...)'];
        } else {
            $rows[] = ['Resend Email API', $mailer, '[MISSING]', 'RESEND_API_KEY is missing in .env'];
        }

        // 2. Africa's Talking SMS API
        $atKey = config('services.africastalking.api_key');
        $atUser = config('services.africastalking.username', 'sandbox');
        $atSender = config('services.africastalking.from', 'NOIRBLOOM');
        if (!empty($atKey) && (str_starts_with($atKey, 'atsk_') || strlen($atKey) >= 16)) {
            $rows[] = ['Africa\'s Talking SMS', $atUser, '[OK]', "Valid key (Sender ID: {$atSender})"];
        } elseif (!empty($atKey)) {
            $rows[] = ['Africa\'s Talking SMS', $atUser, '[OK]', 'Key configured (' . substr($atKey, 0, 8) . '...)'];
        } else {
            $rows[] = ['Africa\'s Talking SMS', $atUser, '[MISSING]', 'AFRICASTALKING_API_KEY is missing in .env'];
        }

        // 3. Safaricom M-Pesa Daraja
        $mpesaKey = config('services.mpesa.key');
        $mpesaShortcode = config('services.mpesa.shortcode', '174379');
        $mpesaEnv = config('services.mpesa.environment', 'sandbox');
        if (!empty($mpesaKey) && !empty($mpesaShortcode)) {
            $rows[] = ['M-Pesa Daraja STK', $mpesaEnv, '[OK]', "Shortcode: {$mpesaShortcode} (Key present)"];
        } else {
            $rows[] = ['M-Pesa Daraja STK', $mpesaEnv, '[WARNING]', 'MPESA_CONSUMER_KEY or Shortcode missing'];
        }

        // 4. KRA eTIMS Tax Fiscalization
        $etimsSerial = config('services.etims.serial', 'SERIAL-DEV-001');
        $etimsPin = config('services.etims.pin', 'P051234567A');
        $etimsEnv = config('services.etims.environment', 'sandbox');
        if (!empty($etimsSerial) && !empty($etimsPin)) {
            $rows[] = ['KRA eTIMS Fiscal', $etimsEnv, '[OK]', "Serial: {$etimsSerial} | PIN: {$etimsPin}"];
        } else {
            $rows[] = ['KRA eTIMS Fiscal', $etimsEnv, '[WARNING]', 'eTIMS Device Serial or PIN missing'];
        }

        $this->table(['Integration API', 'Environment / Driver', 'Status', 'Configuration Details'], $rows);

        $this->newLine();
        $this->info('Audit finished. Resilience fallbacks enabled for non-blocking execution.');

        return Command::SUCCESS;
    }
}
