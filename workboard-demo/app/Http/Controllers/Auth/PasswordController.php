<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PasswordController extends Controller
{
    public function __construct(private readonly UserService $service) {}

    public function firstChange(): Response
    {
        return Inertia::render('Auth/FirstPasswordChange');
    }

    public function completeFirstChange(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->service->changePassword($request->user(), $request->validated('password'));

        return to_route('dashboard')->with('success', 'Password changed successfully. Welcome to WorkBoard!');
    }

    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->service->changePassword($request->user(), $request->validated('password'));

        return back();
    }
}
