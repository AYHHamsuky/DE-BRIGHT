<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\GalleryItem;
use App\Models\Post;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedCourses();
        $this->seedSlides();
        $this->seedGallery();
        $this->seedPosts();
    }

    private function seedAdmin(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@example.com');
        $password = env('ADMIN_PASSWORD') ?: Str::password(12, symbols: false);

        // Only set a password the first time, so re-seeding never resets it.
        $user = User::where('email', $email)->first();

        if (! $user) {
            User::create([
                'name' => env('ADMIN_NAME', 'Site Admin'),
                'email' => $email,
                'password' => $password,
            ]);

            $this->command?->info("Admin created. Login at /admin/login with: {$email} / {$password}");
        }
    }

    private function seedCourses(): void
    {
        // Names, levels and durations come from the school flyer.
        $courses = [
            ['Diploma in Community Health', 'CHEW', 'Diploma', '3 Years', 'Trains Community Health Extension Workers to deliver primary healthcare services, health education and disease prevention in communities.'],
            ['National Diploma in Community Health', 'ND', 'National Diploma', '2 Years', 'A national diploma programme in community health for students who meet the entry requirements.'],
            ['Certificate in Community Health', 'JCHEW', 'Certificate', '2 Years', 'Trains Junior Community Health Extension Workers in basic primary healthcare and community outreach.'],
            ['Medical Laboratory Technology', 'MLT', 'Diploma', '3 Years', 'Practical training in laboratory testing of blood, urine and other samples that support diagnosis and treatment.'],
            ['Diploma in Health Information Management', 'HIM', 'Diploma', '3 Years', 'Covers the collection, coding, storage and management of patient records and health data.'],
            ['Environmental Health Technician', 'EVT', 'Diploma', '3 Years', 'Trains students in sanitation, hygiene, water safety, waste management and environmental disease control.'],
            ['Environmental Health Assistant', 'EHA', 'Diploma', '2 Years', 'Entry-level training in environmental sanitation and public hygiene practice.'],
            ['Diploma in Health Education and Promotion', 'HEP', 'Diploma', '2 Years', 'Prepares students to teach and promote healthy living and disease prevention in communities.'],
            ['Public Health', null, 'ND & HND', '3 Years', 'Covers the prevention of disease and the promotion of population health.'],
            ['Pharmacy Technician', null, 'Diploma', '3 Years', 'Trains students to assist pharmacists in dispensing, storing and managing medicines.'],
        ];

        foreach ($courses as $i => [$name, $code, $level, $duration, $summary]) {
            Course::updateOrCreate(
                ['name' => $name],
                ['code' => $code, 'level' => $level, 'duration' => $duration, 'summary' => $summary, 'sort_order' => $i + 1, 'is_active' => true]
            );
        }
    }

    private function seedSlides(): void
    {
        if (Slide::count() > 0) {
            return;
        }

        $slides = [
            ['De Bright College of Health Science and Technology', 'images/slides/slide-1-banner.jpg'],
            ['Learning for Good Health', 'images/slides/slide-2-flyer.jpg'],
            ['Available courses and entry requirements', 'images/slides/slide-3-courses.jpg'],
        ];

        foreach ($slides as $i => [$title, $image]) {
            Slide::create(['title' => $title, 'image' => $image, 'sort_order' => $i + 1, 'is_active' => true]);
        }
    }

    private function seedGallery(): void
    {
        if (GalleryItem::count() > 0) {
            return;
        }

        $items = [
            ['Student at the microscope', 'images/gallery/lab-microscope.jpg'],
            ['Practical class: taking blood pressure', 'images/gallery/practical-bp.jpg'],
            ['Practical class: patient care demonstration', 'images/gallery/practical-mannequin.jpg'],
            ['Laboratory sample testing', 'images/gallery/lab-samples.jpg'],
        ];

        foreach ($items as $i => [$title, $image]) {
            GalleryItem::create(['title' => $title, 'image' => $image, 'sort_order' => $i + 1, 'is_active' => true]);
        }
    }

    private function seedPosts(): void
    {
        if (Post::count() > 0) {
            return;
        }

        Post::create([
            'title' => 'Applications are open at De Bright College',
            'slug' => 'applications-are-open',
            'excerpt' => 'Collect your application form at the school and start your health career with us.',
            'body' => "De Bright College of Health Science and Technology is receiving applications for its Diploma, National Diploma and Certificate programmes.\n\nTo apply, visit the school at No. 27/28 Adjacent to General Hospital and KLM 127 Command, FRSC Office, Minna Road, Lapai, to collect your application form. You can also call 08064837014, 08038284265 or 09066638080 for more information.",
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
