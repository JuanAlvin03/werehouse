<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseLocationController;
use App\Http\Controllers\BusinessPartnerController;
use App\Http\Controllers\StockAdjustmentReasonController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\CustomerReturnController;
use App\Http\Controllers\SupplierReturnController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data Routes
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('units', UnitController::class);
    Route::resource('products', ProductController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('warehouses.locations', WarehouseLocationController::class);
    Route::resource('business-partners', BusinessPartnerController::class);
    Route::resource('stock-adjustment-reasons', StockAdjustmentReasonController::class);

    // Document Routes
    Route::resource('documents', DocumentController::class, ['except' => 'edit', 'update', 'create', 'store']);
    Route::post('documents/{document}/post', [DocumentController::class, 'post'])->name('documents.post');
    Route::post('documents/{document}/cancel', [DocumentController::class, 'cancel'])->name('documents.cancel');

    // Receipt Routes
    Route::resource('receipts', ReceiptController::class, ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    // Issue Routes
    Route::resource('issues', IssueController::class, ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    // Adjustment Routes
    Route::resource('adjustments', AdjustmentController::class, ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    // Customer Return Routes
    Route::resource('customer-returns', CustomerReturnController::class, ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    // Supplier Return Routes
    Route::resource('supplier-returns', SupplierReturnController::class, ['only' => ['index', 'create', 'store', 'edit', 'update']]);

    // Transfer Routes
    Route::resource('transfers', TransferController::class, ['only' => ['index', 'create', 'store', 'edit', 'update']]);
});

require __DIR__.'/auth.php';
