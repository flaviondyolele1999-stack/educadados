<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estatistica;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class EstatisticaAdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user->escola) {
            return redirect()->route('escola.register');
        }

        return Inertia::render('admin/estatisticas/adminDashboard', [
            'estatisticas' => $user->escola->estatisticas()->latest()->get(),
            'escola' => $user->escola,
        ]);
    }

    public function store(Request $request)
    {
        $escola = $request->user()->escola;
        abort_if(! $escola, 403);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'valor' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $escola->estatisticas()->create($request->only('titulo', 'valor', 'descricao'));

        return redirect()->route('admin.estatisticas.index');
    }

    public function update(Request $request, Estatistica $estatistica)
    {
        abort_if($estatistica->escola_id !== $request->user()->escola?->id, 403);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'valor' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $estatistica->update($request->only('titulo', 'valor', 'descricao'));

        return redirect()->route('admin.estatisticas.index');
    }

    public function destroy(Request $request, Estatistica $estatistica)
    {
        abort_if($estatistica->escola_id !== $request->user()->escola?->id, 403);

        $estatistica->delete();

        return redirect()->route('admin.estatisticas.index');
    }
}
