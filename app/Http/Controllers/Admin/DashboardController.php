<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enquiry;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\Slide;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'counts' => [
                'unread' => Enquiry::where('is_read', false)->count(),
                'enquiries' => Enquiry::count(),
                'courses' => Course::count(),
                'posts' => Post::count(),
                'slides' => Slide::count(),
                'gallery' => GalleryItem::count(),
            ],
            'latest' => Enquiry::with('course')->latest()->take(5)->get(),
        ]);
    }
}
