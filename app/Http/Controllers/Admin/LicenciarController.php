<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LicenciarController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user->escola) {
            return redirect()->route('escola.register');
        }

        return Inertia::render('admin/estatisticas/Licenciar', [
            'escola' => $user->escola,
        ]);
    }
}