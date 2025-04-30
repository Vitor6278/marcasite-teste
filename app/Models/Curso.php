<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
        'valor',
        'data_inicio',
        'data_fim',
        'quantidade_maxima_inscritos', // <<< Adicionado
        'arquivo_material'             // <<< Adicionado
    ];

    /**
     * Get the inscricoes for the curso.
     */
    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class);
    }

    /**
     * The attributes that should be cast.
     * Adicionar casts pode ser útil, especialmente para valor e datas
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'valor' => 'decimal:2', // Exemplo: garante que o valor seja tratado como decimal com 2 casas
    //     'data_inicio' => 'date',
    //     'data_fim' => 'date',
    //     'quantidade_maxima_inscritos' => 'integer',
    // ];
}