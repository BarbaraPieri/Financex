<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaFiscal extends Model
{
    use HasFactory;

    // Defina os campos da nota fiscal aqui
    protected $fillable = [
        'nome',
        'email',
        'cpf',
        'telefone',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'estado',
    ];

    protected $table = 'notas_fiscais';

    // Relacionamento com Pagamento
    public function pagamento()
    {
        return $this->hashOne(Pagamento::class);
    }
}
