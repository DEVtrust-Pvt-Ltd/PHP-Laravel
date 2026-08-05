<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! $this->service->sendVerification($request->user())) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        return back()->with('status', 'verification-link-sent');
    }
}
