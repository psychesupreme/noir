<?php

use App\Livewire\Storefront;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\OrderIndex;
use App\Livewire\Admin\ProductIndex;
use App\Livewire\Admin\ClientIndex;
use App\Livewire\Admin\BranchIndex;
use App\Livewire\Admin\CampaignIndex;
use App\Livewire\Admin\PaymentIndex;
use App\Livewire\Admin\TaxIndex;
use App\Livewire\Admin\ReportIndex;
use App\Livewire\Admin\SettingsIndex;
use App\Http\Controllers\MpesaCallbackController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Noir & Bloom ERP
|--------------------------------------------------------------------------
*/

// ── Public Storefront ────────────────────────────────────────────────────
Route::get('/', Storefront::class)->name('storefront');

// ── Authentication ───────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');

// ── Admin ERP Panel (Protected) ──────────────────────────────────────────
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->group(function () {
    Route::get('/', Dashboard::class)->name('admin.dashboard');
    Route::get('/orders', OrderIndex::class)->name('admin.orders');
    Route::get('/products', ProductIndex::class)->name('admin.products');
    Route::get('/clients', ClientIndex::class)->name('admin.clients');

    // Phase 2+: Future admin routes
    Route::get('/branches', BranchIndex::class)->name('admin.branches');
    Route::get('/campaigns', CampaignIndex::class)->name('admin.campaigns');
    Route::get('/payments', PaymentIndex::class)->name('admin.payments');
    Route::get('/tax', TaxIndex::class)->name('admin.tax');
    Route::get('/reports', ReportIndex::class)->name('admin.reports');
    Route::get('/settings', SettingsIndex::class)->name('admin.settings');
});

// ── M-Pesa Webhook (CSRF exempt — configured in bootstrap/app.php) ───────
Route::post('/api/v1/mpesa/callback', [MpesaCallbackController::class, 'handleCallback'])
    ->name('mpesa.callback');