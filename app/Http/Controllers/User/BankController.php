<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\View\View;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(Request $request): View
    {
        $banks = Bank::search($request->string('search')->toString())->withCount(['templates' => fn ($query) => $query->where('created_by', auth()->id())])->orderBy('name')->paginate(12)->withQueryString();

        return view('user.banks.index', compact('banks'));
    }

    public function show(Bank $bank): View
    {
        $templates = $bank->templates()->where('created_by', auth()->id())->latest()->get();
        $chequesCount = $bank->cheques()->where('user_id', auth()->id())->count();

        return view('user.banks.show', compact('bank', 'templates', 'chequesCount'));
    }
}
