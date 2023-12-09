<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasFiscaisTable extends Migration
{
    public function up()
    {
        Schema::create('notas_fiscais', function (Blueprint $table) {
            $table->id();

            // Adicione a coluna da chave estrangeira
            $table->foreignId('pagamento_id')->constrained('pagamentos');

            // Restante das colunas
            $table->string('nome');
            $table->string('email');
            $table->string('cpf');
            $table->string('telefone');
            $table->string('rua');
            $table->string('numero');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('estado');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas_fiscais');
    }
}
