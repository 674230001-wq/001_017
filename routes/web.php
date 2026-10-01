<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerRepairController;
use App\Http\Controllers\TechnicianRepairController;
use App\Http\Controllers\AdminRepairController;

/*
|--------------------------------------------------------------------------
| Web Routes - Porsche Service Management System
|--------------------------------------------------------------------------
| โครงงานระบบแจ้งซ่อมและบริการศูนย์รถยนต์ปอร์เช่
| ผู้ดูแลระบบ: Raphiphat001 | ช่างเทคนิค: Thanasak 017
*/

// หน้าแรกและการค้นหาติดตามสถานะ
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/track', [HomeController::class, 'track'])->name('track');

// ระบบเข้าสู่ระบบ / สมัครสมาชิก
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ส่วนของลูกค้า (Customer)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/repairs', [CustomerRepairController::class, 'index'])->name('repairs.index');
    Route::get('/repairs/create', [CustomerRepairController::class, 'create'])->name('repairs.create');
    Route::post('/repairs', [CustomerRepairController::class, 'store'])->name('repairs.store');
    Route::get('/repairs/{id}', [CustomerRepairController::class, 'show'])->name('repairs.show');
    Route::post('/repairs/{id}/approve', [CustomerRepairController::class, 'approve'])->name('repairs.approve');
    Route::post('/repairs/{id}/reject', [CustomerRepairController::class, 'reject'])->name('repairs.reject');
    Route::post('/repairs/{id}/feedback', [CustomerRepairController::class, 'feedback'])->name('repairs.feedback');
});

// ส่วนของช่างเทคนิค (Technician: Thanasak 017)
Route::middleware(['auth', 'role:technician'])->prefix('technician')->name('technician.')->group(function () {
    Route::get('/repairs', [TechnicianRepairController::class, 'index'])->name('repairs.index');
    Route::get('/repairs/{id}', [TechnicianRepairController::class, 'show'])->name('repairs.show');
    Route::post('/repairs/{id}/claim', [TechnicianRepairController::class, 'claimJob'])->name('repairs.claim');
    Route::post('/repairs/{id}/status', [TechnicianRepairController::class, 'updateStatus'])->name('repairs.status');
    Route::post('/repairs/{id}/inspection', [TechnicianRepairController::class, 'saveInspection'])->name('repairs.inspection');
    Route::post('/repairs/{id}/items', [TechnicianRepairController::class, 'addItem'])->name('repairs.items.add');
    Route::delete('/repairs/{id}/items/{itemId}', [TechnicianRepairController::class, 'deleteItem'])->name('repairs.items.delete');
    Route::post('/repairs/{id}/discount', [TechnicianRepairController::class, 'updateDiscount'])->name('repairs.discount');
    Route::post('/repairs/{id}/photos', [TechnicianRepairController::class, 'uploadPhoto'])->name('repairs.photos.upload');
});

// ส่วนของผู้ดูแลระบบ (Admin: Raphiphat001)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminRepairController::class, 'dashboard'])->name('dashboard');
    Route::get('/repairs', [AdminRepairController::class, 'index'])->name('repairs.index');
    Route::post('/repairs/{id}/assign', [AdminRepairController::class, 'assignTechnician'])->name('repairs.assign');
    Route::post('/repairs/{id}/status', [AdminRepairController::class, 'updateStatus'])->name('repairs.status');
    Route::get('/repairs/{id}/print', [AdminRepairController::class, 'printSheet'])->name('repairs.print');
    Route::delete('/repairs/{id}', [AdminRepairController::class, 'destroy'])->name('repairs.destroy');
    
    // จัดการผู้ใช้
    Route::get('/users', [AdminRepairController::class, 'usersList'])->name('users.index');
    Route::post('/users', [AdminRepairController::class, 'userStore'])->name('users.store');

    // แคตตาล็อกอะไหล่และบริการ
    Route::get('/catalogs', [AdminRepairController::class, 'catalogList'])->name('catalogs.index');
    Route::post('/catalogs', [AdminRepairController::class, 'catalogStore'])->name('catalogs.store');
});
