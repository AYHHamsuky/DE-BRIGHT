<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

/*
 | Public website
 */
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/courses', [PageController::class, 'courses'])->name('courses');
Route::get('/admissions', [PageController::class, 'admissions'])->name('admissions');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');
Route::get('/news', [PageController::class, 'news'])->name('news');
Route::get('/news/{post:slug}', [PageController::class, 'post'])->name('news.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendEnquiry'])->middleware('throttle:5,10')->name('contact.send');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

/*
 | Admin panel
 */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');

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
    });
});
