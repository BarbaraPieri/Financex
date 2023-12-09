<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor');
            $table->string('nome_cartao');
            $table->string('numero_cartao', 16);
            $table->string('expiracao');
            $table->string('codigo_cvv', 3);
            $table->enum('status', ['CRIADO', 'CONFIRMADO', 'CANCELADO'])->default('CRIADO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
