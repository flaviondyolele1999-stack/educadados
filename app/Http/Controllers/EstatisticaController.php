<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Escola;

class EstatisticaController extends Controller
{
    public function home()
    {
        return Inertia::render('Welcome', [
            'estatisticas' => $this->resumoPublico(),
        ]);
    }

    public function index()
    {
        return Inertia::render('Explorar', [
            'estatisticas' => $this->resumoPublico(),
        ]);
    }

    private function resumoPublico(): array
    {
        $totalEscolas = Escola::count();
        $totalAlunos = Escola::sum('total_alunos');
        $totalProfessores = Escola::sum('total_professores');
        $anoAtual = (string) now()->year;

        return [
            [
                'titulo' => 'Número de Escolas',
                'valor' => number_format($totalEscolas, 0, ',', '.'),
                'descricao' => 'Total de escolas que enviaram dados estatísticos.',
                'ano' => $anoAtual,
            ],
            [
                'titulo' => 'Alunos Matriculados',
                'valor' => number_format($totalAlunos, 0, ',', '.'),
                'descricao' => 'Número total de alunos matriculados nas escolas registadas.',
                'ano' => $anoAtual,
            ],
            [
                'titulo' => 'Professores Ativos',
                'valor' => number_format($totalProfessores, 0, ',', '.'),
                'descricao' => 'Número de professores atualmente em exercício.',
                'ano' => $anoAtual,
            ],
        ];
    }
}
