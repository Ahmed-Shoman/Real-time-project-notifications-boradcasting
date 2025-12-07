<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserLogOut;

class UserLogoutController extends Controller
{
    public function logout(Request $request)
    {
        $user = Auth::user();

        // ارسال notification قبل تسجيل الخروج
        $user->notify(new UserLogOut($user));

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // او أي صفحة بعد logout
    }
}
