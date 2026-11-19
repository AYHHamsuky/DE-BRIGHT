<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaystackWebhookController;
use App\Http\Controllers\Portal;
use Illuminate\Support\Facades\Route;

/*
 | Public website
 */
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/approval', [PageController::class, 'approval'])->name('approval');
Route::get('/courses', [PageController::class, 'courses'])->name('courses');
Route::get('/admissions', [PageController::class, 'admissions'])->name('admissions');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/news', [PageController::class, 'news'])->name('news');
Route::get('/news/{post:slug}', [PageController::class, 'post'])->name('news.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendEnquiry'])->middleware('throttle:5,10')->name('contact.send');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

Route::post('/payments/paystack/webhook', PaystackWebhookController::class)->name('paystack.webhook');

/*
 | Staff area: super admins and admins
 */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
    });

    Route::post('logout', [Admin\AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'role:super_admin,admin'])->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('courses', Admin\CourseController::class)->except('show');
        Route::resource('posts', Admin\PostController::class)->except('show');

        Route::get('slides', [Admin\SlideController::class, 'index'])->name('slides.index');
        Route::post('slides', [Admin\SlideController::class, 'store'])->name('slides.store');
        Route::put('slides/{id}', [Admin\SlideController::class, 'update'])->name('slides.update');
        Route::delete('slides/{id}', [Admin\SlideController::class, 'destroy'])->name('slides.destroy');

        Route::get('gallery', [Admin\GalleryController::class, 'index'])->name('gallery.index');
        Route::post('gallery', [Admin\GalleryController::class, 'store'])->name('gallery.store');
        Route::put('gallery/{id}', [Admin\GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('gallery/{id}', [Admin\GalleryController::class, 'destroy'])->name('gallery.destroy');

        Route::resource('enquiries', Admin\EnquiryController::class)->only(['index', 'show', 'destroy']);

        // Student records and school fees
        Route::resource('students', Admin\StudentController::class);
        Route::resource('fees', Admin\FeeController::class)->except('show');
        Route::get('payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
        Route::get('payments/create', [Admin\PaymentController::class, 'create'])->name('payments.create');
        Route::post('payments', [Admin\PaymentController::class, 'store'])->name('payments.store');
        Route::get('payments/{payment}', [Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::get('payments/{payment}/receipt', [Admin\PaymentController::class, 'receipt'])->name('payments.receipt');
        Route::post('payments/{payment}/verify', [Admin\PaymentController::class, 'verify'])->name('payments.verify');

        Route::get('account', [Admin\AccountController::class, 'edit'])->name('account.edit');
        Route::put('account', [Admin\AccountController::class, 'update'])->name('account.update');
    });

    // Only super admins manage staff accounts.
    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        Route::resource('users', Admin\UserController::class)->except('show');
    });
});

/*
 | Student portal
 */
Route::prefix('portal')->name('portal.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Portal\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Portal\AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
    });

    Route::post('logout', [Portal\AuthController::class, 'logout'])->middleware('auth')->name('logout');

    Route::middleware(['auth', 'role:student'])->group(function () {
        Route::get('/', [Portal\DashboardController::class, 'index'])->name('dashboard');
        Route::get('payments', [Portal\PaymentController::class, 'index'])->name('payments.index');
        Route::post('fees/{fee}/pay', [Portal\PaymentController::class, 'pay'])->middleware('throttle:10,1')->name('fees.pay');
        Route::get('payments/callback', [Portal\PaymentController::class, 'callback'])->name('payments.callback');
        Route::get('payments/{payment:reference}/receipt', [Portal\PaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('password', [Portal\PasswordController::class, 'edit'])->name('password.edit');
        Route::put('password', [Portal\PasswordController::class, 'update'])->name('password.update');
    });
});
