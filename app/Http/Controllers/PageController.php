<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enquiry;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\Slide;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'slides' => Slide::active()->get(),
            'courses' => Course::active()->take(6)->get(),
            'courseCount' => Course::active()->count(),
            'posts' => Post::published()->take(3)->get(),
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function courses()
    {
        return view('pages.courses', ['courses' => Course::active()->get()]);
    }

    public function admissions()
    {
        return view('pages.admissions', ['courses' => Course::active()->get()]);
    }

    public function gallery()
    {
        return view('pages.gallery', ['items' => GalleryItem::active()->get()]);
    }

    public function news()
    {
        return view('pages.news', ['posts' => Post::published()->simplePaginate(9)]);
    }

    public function post(Post $post)
    {
        abort_unless($post->is_published && $post->published_at && $post->published_at->lte(now()), 404);

        return view('pages.post', ['post' => $post]);
    }

    public function contact()
    {
        return view('pages.contact', ['courses' => Course::active()->get()]);
    }

    public function sendEnquiry(Request $request)
    {
        // Honeypot: real visitors never see or fill this field.
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', 'Thank you. We will get back to you soon.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        Enquiry::create($data);

        return redirect()->route('contact')->with('success', 'Thank you. Your message has been received and we will get back to you soon.');
    }

    public function sitemap()
    {
        return response()
            ->view('sitemap', ['posts' => Post::published()->get()])
            ->header('Content-Type', 'application/xml');
    }
}
