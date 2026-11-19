<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enquiry;
use App\Models\GalleryItem;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Slide;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $successful = Payment::where('status', PaymentStatus::Successful);

        return view('admin.dashboard', [
            'counts' => [
                'unread' => Enquiry::where('is_read', false)->count(),
                'enquiries' => Enquiry::count(),
                'courses' => Course::count(),
                'posts' => Post::count(),
                'slides' => Slide::count(),
                'gallery' => GalleryItem::count(),
                'students' => Student::where('status', 'active')->count(),
            ],
            'money' => [
                'today' => (int) (clone $successful)->whereDate('paid_at', today())->sum('amount'),
                'month' => (int) (clone $successful)->where('paid_at', '>=', now()->startOfMonth())->sum('amount'),
                'all' => (int) (clone $successful)->sum('amount'),
            ],
            'latest' => Enquiry::with('course')->latest()->take(5)->get(),
            'latestPayments' => Payment::with('student.user', 'fee')->where('status', PaymentStatus::Successful)->latest('paid_at')->take(5)->get(),
        ]);
    }
}
