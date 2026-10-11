<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FoundationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        return view('user.settings.index');
    }

    public function update(FoundationRequest $request): RedirectResponse
    {
        return redirect()->route('user.settings.index')
            ->with('status', 'This action is not available in Phase 1. No changes were saved.');
    }
}
