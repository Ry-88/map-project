<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (! Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
        return back()
            ->withErrors(['email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.'])
            ->onlyInput('email');
    }

    $user = Auth::guard('web')->user();

    if (! $user->is_active) {
        return $this->rejectLogin($request, 'حسابك غير مفعّل، يرجى التواصل مع المدير.');
    }

    if (! in_array($user->role, ['admin', 'viewer'])) {
        return $this->rejectLogin($request, 'لا توجد صلاحية دخول لهذا الحساب.');
    }

    $request->session()->regenerate();

    return redirect()->intended('/home');
}

private function rejectLogin(Request $request, string $message)
{
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->withErrors(['email' => $message]);
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
