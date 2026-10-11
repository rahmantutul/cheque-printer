<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoundationRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit');
    }

    public function update(FoundationRequest $request, User $user): RedirectResponse
    {
        return redirect()->route('admin.users.index')
            ->with('status', 'This action is not available in Phase 1. No changes were saved.');
    }

    public function destroy(FoundationRequest $request, User $user): RedirectResponse
    {
        return redirect()->route('admin.users.index')
            ->with('status', 'This action is not available in Phase 1. No changes were saved.');
    }
}
