<?php

namespace Tests\Feature;

use App\Models\Slide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_the_sliding_images(): void
    {
        Slide::create(['title' => 'Banner', 'image' => 'images/slides/slide-1-banner.jpg', 'sort_order' => 1, 'is_active' => true]);
        Slide::create(['title' => 'Flyer', 'image' => 'images/slides/slide-2-flyer.jpg', 'sort_order' => 2, 'is_active' => true]);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-slider', false)
            ->assertSee('slide-1-banner.jpg', false)
            ->assertSee('slide-2-flyer.jpg', false);
    }

    public function test_public_pages_load(): void
    {
        foreach (['/about', '/courses', '/admissions', '/gallery', '/news', '/contact', '/sitemap.xml'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_contact_form_saves_an_enquiry(): void
    {
        $this->post('/contact', [
            'name' => 'Aisha Musa',
            'phone' => '08000000000',
            'message' => 'Please send me admission details.',
        ])->assertRedirect('/contact');

        $this->assertDatabaseHas('enquiries', ['name' => 'Aisha Musa']);
    }

    public function test_contact_form_requires_name_phone_and_message(): void
    {
        $this->post('/contact', [])->assertSessionHasErrors(['name', 'phone', 'message']);
    }

    public function test_admin_area_needs_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_log_in(): void
    {
        $user = User::factory()->create(['password' => 'a-strong-password-123']);

        $this->post('/admin/login', ['email' => $user->email, 'password' => 'a-strong-password-123'])
            ->assertRedirect('/admin');

        $this->get('/admin')->assertOk();
    }
}
