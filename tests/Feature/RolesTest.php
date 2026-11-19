<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_students_but_not_staff_accounts(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/students')->assertOk();
        $this->actingAs($admin)->get('/admin/users')->assertForbidden();
    }

    public function test_super_admin_can_create_an_admin(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->post('/admin/users', [
            'name' => 'Bursar',
            'email' => 'bursar@example.com',
            'role' => 'admin',
            'password' => 'bursar-password-1',
            'is_active' => '1',
        ])->assertRedirect('/admin/users');

        $this->assertSame(Role::Admin, User::where('email', 'bursar@example.com')->first()->role);
    }

    public function test_super_admin_cannot_demote_themselves(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)->put("/admin/users/{$superAdmin->id}", [
            'name' => $superAdmin->name,
            'email' => $superAdmin->email,
            'role' => 'admin',
            'is_active' => '1',
        ])->assertSessionHasErrors('role');

        $this->assertSame(Role::SuperAdmin, $superAdmin->fresh()->role);
    }

    public function test_only_super_admin_can_delete_a_student(): void
    {
        $student = Student::factory()->create();

        $this->actingAs(User::factory()->create())->delete("/admin/students/{$student->id}")->assertForbidden();
        $this->actingAs(User::factory()->superAdmin()->create())->delete("/admin/students/{$student->id}")->assertRedirect('/admin/students');

        $this->assertModelMissing($student);
    }

    public function test_student_cannot_open_the_admin_area(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($student->user)->get('/admin')->assertForbidden();
    }

    public function test_staff_cannot_open_the_student_portal(): void
    {
        $this->actingAs(User::factory()->create())->get('/portal')->assertForbidden();
    }

    public function test_student_cannot_log_in_through_the_admin_login(): void
    {
        $student = Student::factory()->create();
        $student->user->update(['password' => 'student-pass-123']);

        $this->post('/admin/login', ['email' => $student->user->email, 'password' => 'student-pass-123'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_deactivated_staff_cannot_log_in(): void
    {
        $admin = User::factory()->inactive()->create(['password' => 'a-strong-password-123']);

        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'a-strong-password-123'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_guest_is_sent_to_the_portal_login(): void
    {
        $this->get('/portal')->assertRedirect('/portal/login');
    }

    public function test_student_logs_in_with_registration_number(): void
    {
        $student = Student::factory()->create(['reg_number' => 'DBC/2026/MLT/001']);
        $student->user->update(['password' => 'student-pass-123']);

        $this->post('/portal/login', ['login' => 'DBC/2026/MLT/001', 'password' => 'student-pass-123'])
            ->assertRedirect('/portal');

        $this->assertAuthenticatedAs($student->user);
    }

    public function test_student_login_rejects_wrong_password(): void
    {
        Student::factory()->create(['reg_number' => 'DBC/2026/MLT/002']);

        $this->post('/portal/login', ['login' => 'DBC/2026/MLT/002', 'password' => 'wrong-password'])
            ->assertSessionHasErrors('login');

        $this->assertGuest();
    }

    public function test_admin_adds_a_student_who_can_then_log_in(): void
    {
        $this->actingAs(User::factory()->create())->post('/admin/students', [
            'name' => 'Aisha Musa',
            'reg_number' => 'DBC/2026/CHEW/014',
            'password' => 'first-password-1',
            'status' => 'active',
        ])->assertRedirect();

        $student = Student::where('reg_number', 'DBC/2026/CHEW/014')->first();
        $this->assertSame(Role::Student, $student->user->role);
        $this->assertStringEndsWith('@'.config('school.student_email_domain'), $student->user->email);
    }
}
