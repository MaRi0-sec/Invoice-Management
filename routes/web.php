<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceArchiveController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Livewire\Actions\Logout;



Route::view('/', 'pages.auth.login')->name('login');

/*
|--------------------------------------------------------------------------
| Forget Password
|--------------------------------------------------------------------------
*/

Route::get('/forget-password', [ForgotPasswordController::class, 'index'])->name('forgetPassword');

/*
|--------------------------------------------------------------------------
| Reset Password
|--------------------------------------------------------------------------
*/

Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('resetPassword');
Route::post('/update-password', [ResetPasswordController::class, 'update'])->name('passwordUpdate');


Route::middleware(['auth', 'verified', 'check.active'])->group(function () {

    Route::get('home', [HomeController::class, 'index'])->name('home');

    Route::get('/user-profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/update-profile/{id}', [ProfileController::class, 'update'])->name('update-profile');

    Route::get('/logout', Logout::class)->name('logout');

    /*
    |--------------------------------------------------------------------------
    | invoices
    |--------------------------------------------------------------------------
    */

    Route::resource('/invoice', InvoiceController::class)
        ->middleware('permission:قائمة الفواتير');

    Route::post('/invoice-show', [InvoiceController::class, 'show'])
        ->name('invoiceShow')
        ->middleware('permission:قائمة الفواتير');

    Route::get('/get_products/{section_id}', [InvoiceController::class, 'getProducts']);

    Route::get('/invoice-partial', [InvoiceController::class, 'partialInvoices'])
        ->name('partialInvoices')
        ->middleware('permission:الفواتير المدفوعة جزئيا');

    Route::get('/invoice/status-edit/{id}', [InvoiceController::class, 'statusEdit'])
        ->name('statusEdit')
        ->middleware('permission:تغير حالة الدفع');

    Route::put('/invoice-status-update', [InvoiceController::class, 'statusUpdate'])
        ->name('statusUpdate')
        ->middleware('permission:تغير حالة الدفع');

    Route::get('/invoice/print/{id}', [InvoiceController::class, 'printInvoices'])
        ->name('printInvoices')
        ->middleware('permission:طباعةالفاتورة');

    Route::get('/invoice-export', [InvoiceController::class, 'exportInvoices'])
        ->name('exportInvoices')
        ->middleware('permission:تصدير EXCEL');


    /*
    |--------------------------------------------------------------------------
    | users
    |--------------------------------------------------------------------------
    */

    Route::resource('/Users', UserController::class)
        ->middleware('permission:قائمة المستخدمين');


    /*
    |--------------------------------------------------------------------------
    | roles
    |--------------------------------------------------------------------------
    */

    Route::resource('/Roles', RoleController::class)
        ->middleware('permission:صلاحيات المستخدمين');


    /*
    |--------------------------------------------------------------------------
    | sections
    |--------------------------------------------------------------------------
    */

    Route::resource('/section', SectionController::class)
        ->middleware('permission:الاقسام');


    /*
    |--------------------------------------------------------------------------
    | products
    |--------------------------------------------------------------------------
    */

    Route::resource('/product', ProductController::class)
        ->middleware('permission:المنتجات');


    /*
    |--------------------------------------------------------------------------
    | invoice details
    |--------------------------------------------------------------------------
    */

    Route::resource('/invoiceDetails', InvoiceDetailController::class)
        ->middleware('permission:قائمة الفواتير');


    /*
    |--------------------------------------------------------------------------
    | archive invoices
    |--------------------------------------------------------------------------
    */

    Route::resource('/archive', InvoiceArchiveController::class)
        ->middleware('permission:ارشيف الفواتير');

    Route::put('/archive/restore/{invoice_id}', [InvoiceArchiveController::class, 'restore'])
        ->middleware('permission:ارجاع الفاتوره');


    /*
    |--------------------------------------------------------------------------
    | attachments
    |--------------------------------------------------------------------------
    */

    Route::resource('/invoiceAttachment', InvoiceAttachmentController::class)
        ->middleware('permission:اضافة مرفق');

    Route::get('/attachment-download/{invoice_number}/{file_name}', [InvoiceAttachmentController::class, 'download_file'])
        ->name('download_file')
        ->middleware('permission:اضافة مرفق');
});

require __DIR__ . '/settings.php';
