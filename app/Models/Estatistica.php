<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estatistica extends Model
{
    protected $fillable = ['escola_id', 'titulo', 'valor', 'descricao'];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }
}
