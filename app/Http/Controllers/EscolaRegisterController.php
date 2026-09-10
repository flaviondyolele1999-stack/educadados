<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Escola;
use Inertia\Inertia;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EscolaRegisterController extends Controller
{
    /**
     * Exibir formulário de registro da escola
     */
    public function create()
    {
        if (Auth::check() && Auth::user()->escola) {
            return redirect()->route('admin.estatisticas.index');
        }

        return Inertia::render('escola/registar');
    }

    /**
     * Criar usuário e escola
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->escola) {
            return redirect()->route('admin.estatisticas.index');
        }

        // Validação
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'nome' => 'required|string|max:255',
            'provincia' => 'nullable|string|max:255',
            'municipio' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Criação do usuário
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                // Criação da escola
                Escola::create([
                    'user_id' => $user->id,
                    'nome' => $request->nome,
                    'provincia' => $request->provincia,
                    'municipio' => $request->municipio,
                    'telefone' => $request->telefone,
                    'total_alunos' => 0,
                    'total_professores' => 0,
                    'total_cursos' => 0,
                    'total_classes' => 0,
                ]);

                // Login automático
                Auth::login($user);
            });

            return redirect()->route('admin.estatisticas.index');

        } catch (\Exception $e) {
            // Log completo do erro
            Log::error('Erro ao criar usuário e escola: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['msg' => 'Ocorreu um erro ao criar o usuário e a escola.']);
        }
    }

    /**
     * Atualizar declaração de totais da escola
     */
    public function saveDeclaracao(Request $request, Escola $escola)
    {
        abort_if($escola->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'alunos' => 'required|integer|min:0',
            'professores' => 'required|integer|min:0',
            'turmas' => 'nullable|integer|min:0',
            'cursos' => 'nullable|integer|min:0',
        ]);

        // Atualizar campos da escola
        $escola->update([
            'total_alunos' => $data['alunos'],
            'total_professores' => $data['professores'],
            'total_classes' => $data['turmas'] ?? 0,
            'total_cursos' => $data['cursos'] ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Declaração salva com sucesso!',
            'declaration' => $data
        ]);
    }
}
