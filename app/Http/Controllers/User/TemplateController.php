<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreTemplateRequest;
use App\Http\Requests\User\UpdateTemplateRequest;
use App\Models\Bank;
use App\Models\ChequeTemplate;
use App\Services\ChequeTemplateService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use InvalidArgumentException;

class TemplateController extends Controller
{
    public function __construct(private readonly ChequeTemplateService $templateService) {}

    public function index(): View
    {
        $templates = ChequeTemplate::forUser(auth()->id())->with('bank')->withCount('cheques')->latest()->paginate(12);

        return view('user.templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('user.templates.create', ['banks' => Bank::orderBy('name')->get(), 'positions' => (new ChequeTemplate)->defaultFieldPositions(), 'enabledFields' => ['payee', 'amount_digits', 'amount_words_1', 'amount_words_2', 'date'], 'template' => null, 'widthMm' => 203, 'heightMm' => 90, 'fontSizePt' => 12]);
    }

    public function store(StoreTemplateRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['field_positions'] = json_decode($data['field_positions'], true, 512, JSON_THROW_ON_ERROR);
            $data['enabled_fields'] = $data['enabled_fields'] ?? ['payee', 'amount_digits', 'amount_words_1', 'amount_words_2', 'date'];
            $template = $this->templateService->create($data, $request->file('background_image'), auth()->id());
        } catch (\Throwable $e) {
            if ($e instanceof InvalidArgumentException || $e instanceof \JsonException) {
                return back()->withInput()->withErrors(['field_positions' => 'Field positions are invalid.']);
            } throw $e;
        }

        return redirect()->route('user.templates.show', $template)->with('success', 'Template created successfully.');
    }

    public function show(ChequeTemplate $template): View
    {
        Gate::authorize('view', $template);
        $template->load('bank')->loadCount('cheques');

        return view('user.templates.show', compact('template'));
    }

    public function edit(ChequeTemplate $template): View
    {
        Gate::authorize('update', $template);

        return view('user.templates.edit', ['template' => $template, 'banks' => Bank::orderBy('name')->get(), 'positions' => $template->field_positions, 'enabledFields' => $template->effectiveEnabledFields(), 'widthMm' => $template->width_mm, 'heightMm' => $template->height_mm, 'fontSizePt' => $template->font_size_pt ?? 12]);
    }

    public function update(UpdateTemplateRequest $request, ChequeTemplate $template): RedirectResponse
    {
        Gate::authorize('update', $template);
        $data = $request->validated();
        $data['field_positions'] = json_decode($data['field_positions'], true);
        $data['enabled_fields'] = $data['enabled_fields'] ?? ['payee', 'amount_digits', 'amount_words_1', 'amount_words_2', 'date'];
        $this->templateService->update($template, $data, $request->file('background_image'));

        return redirect()->route('user.templates.show', $template)->with('success', 'Template updated successfully.');
    }

    public function destroy(Request $request, ChequeTemplate $template): RedirectResponse
    {
        Gate::authorize('delete', $template);
        try {
            $this->templateService->delete($template, $request->boolean('force'));
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('user.templates.index')->with('success', 'Template deleted successfully.');
    }
}
