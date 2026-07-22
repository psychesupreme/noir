<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:keep-alive', function () {
    $this->info('Starting Keep-Alive Ping...');
    
    // 1. Force a query to the database to wake/keep-alive Neon PostgreSQL
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $this->info('Neon Database Connection: Active');
        \Illuminate\Support\Facades\Log::info('Keep-Alive: Database connection pinged successfully.');
    } catch (\Exception $e) {
        $this->error('Neon Database Error: ' . $e->getMessage());
        \Illuminate\Support\Facades\Log::error('Keep-Alive Database Error: ' . $e->getMessage());
    }

    // 2. Perform self-ping to keep Fly.io app active from external context (if configured)
    $url = config('app.url') . '/api/v1/status';
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
        $this->info('Self-ping URL: ' . $url . ' - Code: ' . $response->status());
    } catch (\Exception $e) {
        $this->error('Self-ping URL failed: ' . $e->getMessage());
    }
})->purpose('Ping database and web endpoints to prevent cold starts');
