<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::query()->orderBy('sort_order')->orderBy('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.faqs.form', [
            'faq' => null,
        ]);
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        Faq::query()->create($request->validated());

        return redirect()->route('portal.admin.faqs.index')->with('status', 'FAQ berhasil ditambahkan.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.form', [
            'faq' => $faq,
        ]);
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $faq->update($request->validated());

        return redirect()->route('portal.admin.faqs.index')->with('status', 'FAQ diperbarui.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('portal.admin.faqs.index')->with('status', 'FAQ telah dihapus.');
    }
}
