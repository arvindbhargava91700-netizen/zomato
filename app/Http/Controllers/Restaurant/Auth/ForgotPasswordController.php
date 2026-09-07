<?php

namespace App\Http\Controllers\Restaurant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show Forgot Password view for Restaurant Owner.
     */
    public function showLinkRequestForm(): View
    {
        return view('manage.restaurant.auth.forgot-password');
    }
}
