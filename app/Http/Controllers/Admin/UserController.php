<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Staff accounts (super admins and admins). Only super admins reach this controller.
 */
class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::whereIn('role', [Role::SuperAdmin, Role::Admin])->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(['role' => Role::Admin, 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Staff account created.');
    }

    public function edit(User $user)
    {
        abort_unless($user->isStaff(), 404);

        return view('admin.users.form', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->isStaff(), 404);

        $data = $this->validated($request, $user);

        if ($user->is($request->user()) && ($data['role'] !== Role::SuperAdmin->value || ! $data['is_active'])) {
            return back()->withErrors(['role' => 'You cannot remove your own super admin access or deactivate yourself.']);
        }

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Staff account updated.');
    }

    public function destroy(Request $request, User $user)
    {
        abort_unless($user->isStaff(), 404);

        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Staff account deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user?->id)],
            'role' => ['required', Rule::in([Role::SuperAdmin->value, Role::Admin->value])],
            'password' => [$user ? 'nullable' : 'required', 'string', Password::min(8)],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
