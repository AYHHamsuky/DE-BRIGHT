<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('portal.login');
    }

    /**
     * Students log in with their registration number (or email) and password.
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($data['login']);

        $student = Student::with('user')
            ->where('reg_number', $login)
            ->orWhereHas('user', fn ($query) => $query->where('email', $login))
            ->first();

        $user = $student?->user;

        if (! $user || ! Hash::check($data['password'], $user->password) || $user->isStaff()) {
            return back()->withErrors(['login' => 'Wrong registration number or password.'])->onlyInput('login');
        }

        if (! $user->is_active) {
            return back()->withErrors(['login' => 'This account has been deactivated. Please contact the school.'])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('portal.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}
