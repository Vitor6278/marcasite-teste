<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Inscricao extends Model
    {
        use HasFactory;

        protected $table = 'inscricoes'; 

        protected $fillable = [
            'curso_id',
            'user_id', 
            'nome_aluno',
            'email_aluno',
            'cpf',
            'endereco',
            'empresa',
            'telefone_aluno',
            'celular',
            'categoria',
            'status'
        ];

        /**
         * Get the curso associated with the inscricao.
         */
        public function curso()
        {
            return $this->belongsTo(Curso::class);
        }

        /**
         * Get the user associated with the inscricao (if any).
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
    