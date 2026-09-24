<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AdmissionInformation;
use App\Models\Faq;
use Illuminate\View\View;

class AdmissionController extends Controller
{
    public function show(): View
    {
        return view('public.admission', [
            'groups' => AdmissionInformation::query()
                ->published()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->groupBy('type'),
            'faqs' => Faq::query()->published()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
