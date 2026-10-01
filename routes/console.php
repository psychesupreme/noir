<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:health-check', function () {
    $this->info('Running System Health Check...');
    
    // Check database connection status
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $this->info('Database Connection: Operational');
    } catch (\Exception $e) {
        $this->error('Database Connection Error: ' . $e->getMessage());
    }

    // Check status endpoint
    $url = config('app.url') . '/api/v1/status';
    try {
        $response = \Illuminate\Support\Facades\Http::timeout(5)->get($url);
        $this->info('API Status Endpoint (' . $url . '): HTTP ' . $response->status());
    } catch (\Exception $e) {
        $this->warn('API Status Endpoint could not be reached: ' . $e->getMessage());
    }
})->purpose('Check database and API status');
