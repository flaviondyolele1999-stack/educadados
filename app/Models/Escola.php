<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    protected $fillable = [
        'user_id',
        'nome',
        'provincia',
        'municipio',
        'telefone',
        
        'total_alunos',
        'total_professores',
        'total_classes',
        'total_cursos',
        'ano_letivo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function estatisticas()
    {
        return $this->hasMany(Estatistica::class);
    }
}
