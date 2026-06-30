<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::active()
            ->orderBy('name')
            ->paginate(9);

        return view('services.index', compact('services'));
    }

    public function show(Service $service): View
    {
        abort_unless($service->active, 404);

        $professionals = \App\Models\Professional::active()
            ->orderBy('name')
            ->get();

        return view('services.show', compact('service', 'professionals'));
    }
}
