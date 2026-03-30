<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ServiceItemController;
use App\Http\Controllers\Admin\StockController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    // ── DASHBOARD ──────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ── GLOBAL SEARCH ──────────────────────────────────────────────
    Route::get('/search',          [SearchController::class, 'global'])->name('search');
    Route::get('/search/vehicle',  [SearchController::class, 'vehicle'])->name('search.vehicle');
    Route::get('/search/customer', [SearchController::class, 'customerSearch'])->name('search.customer');

    // ── CUSTOMERS ──────────────────────────────────────────────────
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{customer}/vehicles-json',
        [CustomerController::class, 'vehiclesJson'])->name('customers.vehicles-json');

    // ── VEHICLES ───────────────────────────────────────────────────
    Route::resource('vehicles', VehicleController::class);

    // ── SERVICES ───────────────────────────────────────────────────
    Route::resource('services', ServiceController::class);
    Route::post('/services/{service}/complete',
        [ServiceController::class, 'markComplete'])->name('services.complete');

    // ── CHECKLISTS ─────────────────────────────────────────────────
    Route::get('/services/{service}/checklist/create',
        [ChecklistController::class, 'create'])->name('checklists.create');
    Route::post('/services/{service}/checklist',
        [ChecklistController::class, 'store'])->name('checklists.store');
    Route::get('/services/{service}/checklist/{checklist}/edit',
        [ChecklistController::class, 'edit'])->name('checklists.edit');
    Route::put('/services/{service}/checklist/{checklist}',
        [ChecklistController::class, 'update'])->name('checklists.update');

    // ── PARTS ──────────────────────────────────────────────────────
    Route::get('/parts', [PartController::class, 'allIndex'])->name('parts.index');
    Route::resource('services.parts', PartController::class)
        ->shallow()->except(['index', 'show']);

    // ── REPAIRS ────────────────────────────────────────────────────
    Route::get('/repairs', [RepairController::class, 'allIndex'])->name('repairs.index');
    Route::resource('services.repairs', RepairController::class)
        ->shallow()->except(['index', 'show']);

    // ── PAYMENTS ───────────────────────────────────────────────────
    Route::get('/payments', [PaymentController::class, 'allIndex'])->name('payments.index');

    Route::get('/services/{service}/payments/create',
        [PaymentController::class, 'create'])->name('services.payments.create');

    Route::post('/services/{service}/payments',
        [PaymentController::class, 'store'])->name('services.payments.store');

    Route::post('/services/{service}/payments/mpesa/push',
        [PaymentController::class, 'mpesaPush'])->name('services.payments.mpesa.push');

    Route::post('/services/{service}/payments/mpesa/query',
        [PaymentController::class, 'mpesaQuery'])->name('services.payments.mpesa.query');

    Route::post('/services/{service}/payments/mpesa/manual',
        [PaymentController::class, 'mpesaManual'])->name('services.payments.mpesa.manual');

    Route::delete('/payments/{payment}',
        [PaymentController::class, 'destroy'])->name('payments.destroy');

    // ── INVOICES ───────────────────────────────────────────────────
    Route::get('/services/{service}/invoice',
        [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/services/{service}/invoice/generate',
        [InvoiceController::class, 'generate'])->name('invoices.generate');
    Route::get('/services/{service}/invoice/pdf',
        [InvoiceController::class, 'pdf'])->name('invoices.pdf');

    // ── REPORTS ────────────────────────────────────────────────────
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/date',         [ReportController::class, 'byDate'])->name('date');
        Route::get('/vehicle-type', [ReportController::class, 'byVehicleType'])->name('vehicle-type');
        Route::get('/service-type', [ReportController::class, 'byServiceType'])->name('service-type');
        Route::get('/custom',       [ReportController::class, 'custom'])->name('custom');
    });

    // ── ADMIN ──────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/users',  [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::patch('/users/{user}/toggle',
            [AdminController::class, 'toggleUser'])->name('users.toggle');

        Route::get('/catalog',  [AdminController::class, 'catalog'])->name('catalog');

        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        Route::resource('service-items', ServiceItemController::class)
            ->names([
                'index'   => 'service-items.index',
                'create'  => 'service-items.create',
                'store'   => 'service-items.store',
                'edit'    => 'service-items.edit',
                'update'  => 'service-items.update',
                'destroy' => 'service-items.destroy',
            ]);
        Route::patch('service-items/{serviceItem}/toggle',
            [ServiceItemController::class, 'toggle'])->name('service-items.toggle');

        Route::resource('stock', StockController::class)
            ->names([
                'index'   => 'stock.index',
                'create'  => 'stock.create',
                'store'   => 'stock.store',
                'edit'    => 'stock.edit',
                'update'  => 'stock.update',
                'destroy' => 'stock.destroy',
            ]);
        Route::post('stock/{stock}/add',
            [StockController::class, 'addStock'])->name('stock.add');
        Route::get('stock/{stock}/transactions',
            [StockController::class, 'transactions'])->name('stock.transactions');
    });

    // ── HELP ───────────────────────────────────────────────────────
    Route::get('/help', function () {
        return view('help.index');
    })->name('help.index');
});

require __DIR__.'/auth.php';