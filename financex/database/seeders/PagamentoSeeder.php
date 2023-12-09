<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pagamento;

class PagamentoSeeder extends Seeder
{
    public function run()
    {
        // Exemplo de inserção de dados na tabela pagamentos
        Pagamento::create([
            'valor' => 100.00,
            'nome_cartao' => 'João Silva',
            'numero_cartao' => '1234567890123456',
            'expiracao' => '2023-12',
            'codigo_cvv' => 123,
        ]);

        // Adicione mais registros conforme necessário
    }
}
