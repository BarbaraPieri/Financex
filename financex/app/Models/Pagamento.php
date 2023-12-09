<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    // Defina os campos do pagamento aqui
    protected $fillable = [
        'valor',
        'nome_cartao',
        'numero_cartao',
        'expiracao',
        'codigo_cvv',
        'status',
    ];

    // Relacionamento com NotaFiscal
    public function notaFiscal()
    {
        return $this->hasOne(NotaFiscal::class);
    }
}
