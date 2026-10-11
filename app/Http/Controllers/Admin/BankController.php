<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBankRequest;
use App\Http\Requests\Admin\UpdateBankRequest;
use App\Models\Bank;
use App\Services\BankService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankController extends Controller
{
    public function __construct(private readonly BankService $bankService) {}

    public function index(Request $request): View
    {
        $banks = Bank::query()->withCount(['chequeTemplates', 'cheques'])->search($request->string('q')->toString())
            ->when($request->filled('sort'), fn ($query) => $query->orderBy($request->string('sort')->toString(), $request->string('dir', 'asc')->toString()), fn ($query) => $query->orderBy('name'))
            ->paginate(15)->withQueryString();

        return view('admin.banks.index', compact('banks'));
    }

    public function create(): View
    {
        return view('admin.banks.create');
    }

    public function store(StoreBankRequest $request): RedirectResponse
    {
        $bank = $this->bankService->create($request->validated(), $request->file('logo'));

        return redirect()->route('admin.banks.show', $bank)->with('success', 'Bank created successfully.');
    }

    public function show(Bank $bank): View
    {
        $bank->loadCount(['chequeTemplates', 'cheques'])->load(['chequeTemplates' => fn ($query) => $query->latest()->limit(5)]);

        return view('admin.banks.show', compact('bank'));
    }

    public function edit(Bank $bank): View
    {
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(UpdateBankRequest $request, Bank $bank): RedirectResponse
    {
        $this->bankService->update($bank, $request->validated(), $request->file('logo'));

        return redirect()->route('admin.banks.show', $bank)->with('success', 'Bank updated successfully.');
    }

    public function destroy(Request $request, Bank $bank): RedirectResponse
    {
        try {
            $this->bankService->delete($bank, $request->boolean('force'));
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('admin.banks.index')->with('success', 'Bank deleted successfully.');
    }

    public function toggleDefault(Bank $bank): RedirectResponse
    {
        $this->bankService->toggleDefault($bank);

        return back()->with('success', 'Default bank updated.');
    }
}
