<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\InvoiceArchiveController;
use App\Http\Controllers\Api\InvoiceAttachmentController;
use App\Http\Controllers\Api\InvoiceDetailController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;

// Auth Management Routes (Public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLink']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Invoices & Details
    |--------------------------------------------------------------------------
    */
    Route::apiResource('/invoices', InvoiceController::class)
        ->middleware('permission:قائمة الفواتير');

    Route::post('/invoices/search', [InvoiceController::class, 'search'])
        ->middleware('permission:قائمة الفواتير');

    Route::post('/invoices/status', [InvoiceController::class, 'updateStatus'])
        ->middleware('permission:تغير حالة الدفع');

    Route::get('/invoices/export', [InvoiceController::class, 'exportInvoices'])
        ->middleware('permission:تصدير EXCEL');

    Route::get('/sections/{id}/products', [InvoiceController::class, 'getProducts']);

    Route::get('/invoicesdetails/{id}', [InvoiceDetailController::class, 'index'])
        ->middleware('permission:قائمة الفواتير');


    /*
    |--------------------------------------------------------------------------
    | Attachments
    |--------------------------------------------------------------------------
    */
    Route::post('/invoices/attachment/delete/{id}', [InvoiceAttachmentController::class, 'destroy'])
        ->middleware('permission:حذف المرفق');

    Route::post('/invoices/attachment/store', [InvoiceAttachmentController::class, 'store'])
        ->middleware('permission:اضافة مرفق');


    /*
    |--------------------------------------------------------------------------
    | Archive
    |--------------------------------------------------------------------------
    */
    Route::get('/archive/invoices', [InvoiceArchiveController::class, 'index'])
        ->middleware('permission:ارشيف الفواتير');

    Route::post('/archive/restore/{id}', [InvoiceArchiveController::class, 'restore'])
        ->middleware('permission:ارجاع الفاتوره');

    Route::delete('/archive/force-delete/{id}', [InvoiceArchiveController::class, 'destroy'])
        ->middleware('permission:حذف الفاتورة');


    /*
    |--------------------------------------------------------------------------
    | Inventory Management (Products & Sections)
    |--------------------------------------------------------------------------
    */
    Route::get('/products', [ProductController::class, 'index'])->middleware('permission:المنتجات');
    Route::post('/products', [ProductController::class, 'store'])->middleware('permission:المنتجات');
    Route::put('/products/{id}', [ProductController::class, 'update'])->middleware('permission:المنتجات');
    Route::delete('/products/delete/{id}', [ProductController::class, 'destroy'])->middleware('permission:المنتجات');

    Route::apiResource('sections', SectionController::class)
        ->middleware('permission:الاقسام');


    /*
    |--------------------------------------------------------------------------
    | User & Role Management
    |--------------------------------------------------------------------------
    */
    Route::apiResource('users', UserController::class)
        ->middleware('permission:قائمة المستخدمين');

    Route::apiResource('roles', RoleController::class)
        ->middleware('permission:صلاحيات المستخدمين');


    /*
    |--------------------------------------------------------------------------
    | Home & Auth
    |--------------------------------------------------------------------------
    */
    Route::get('/home', [HomeController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
