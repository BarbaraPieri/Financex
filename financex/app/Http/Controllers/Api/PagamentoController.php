<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;


class PagamentoController extends Controller
{
    public function index()
    {
        // Obter todos os pagamentos
        $pagamentos = Pagamento::all();


        return response()->json($pagamentos);
    }

    public function show($id)
    {
        try {
            $pagamento = Pagamento::findOrFail($id);
            return response()->json($pagamento);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Pagamento não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno no servidor'], 500);
        }
    }

    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'nome_cartao' => 'required',
            'numero_cartao' => 'required|digits:16|numeric',
            'expiracao' => 'required|date_format:Y-m|after_or_equal:' . now()->format('Y-m'),
            'codigo_cvv' => 'required|digits:3|numeric',
        ]);

        // Criação do pagamento
        $pagamento = Pagamento::create([
            'valor' => $request->input('valor'),
            'nome_cartao' => $request->input('nome_cartao'),
            'numero_cartao' => $request->input('numero_cartao'),
            'expiracao' => $request->input('expiracao'),
            'codigo_cvv' => $request->input('codigo_cvv'),
        ]);

        return response()->json(['id_pagamento' => $pagamento->id], 201);
    }

    public function destroy($idPagamento)
    {
        try {
            $idPagamento = (int) $idPagamento;

            $pagamento = Pagamento::find($idPagamento);

            if (!$pagamento) {
                return response()->json(['error' => 'Pagamento não encontrado'], 404);
            }

            if ($pagamento->status !== 'CRIADO') {
                return response()->json(['error' => 'Não é possível cancelar um pagamento neste estado'], 400);
            }

            $pagamento->update(['status' => 'CANCELADO']);

            return response()->json([], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro interno no servidor'], 500);
        }
    }

    public function update(Request $request, $id)
{
    $id = (int) $id;

    $pagamento = Pagamento::find($id);

    if (!$pagamento) {
        return response()->json(['error' => 'Pagamento não encontrado'], 404);
    }

    $request->validate([
        'valor' => 'required|numeric|min:0.01',
        'nome_cartao' => 'required',
        'numero_cartao' => 'required|digits:16|numeric',
        'expiracao' => 'required|date_format:Y-m|after_or_equal:' . now()->format('Y-m'),
        'codigo_cvv' => 'required|digits:3|numeric',
    ]);

    // Atualização dos dados do pagamento
    $pagamento->update([
        'valor' => $request->input('valor'),
        'nome_cartao' => $request->input('nome_cartao'),
        'numero_cartao' => $request->input('numero_cartao'),
        'expiracao' => $request->input('expiracao'),
        'codigo_cvv' => $request->input('codigo_cvv'),
    ]);

    // Verifica se já existe uma nota fiscal associada
    if (!$pagamento->notaFiscal) {
        // Se não existir, cria uma nova nota fiscal
        $notaFiscal = $pagamento->notaFiscal()->create([
            'nome' => $request->input('nome'),
            'email' => $request->input('email'),
            'cpf' => $request->input('cpf'),
            'telefone' => $request->input('telefone'),
            'rua' => $request->input('rua'),
            'numero' => $request->input('numero'),
            'bairro' => $request->input('bairro'),
            'cidade' => $request->input('cidade'),
            'estado' => $request->input('estado'),
        ]);

        // Atualiza o status do pagamento para CONFIRMADO
        $pagamento->update(['status' => 'CONFIRMADO']);

        return response()->json([
            'message' => 'Pagamento confirmado com sucesso!',
            'id_pagamento' => $pagamento->id,
            'status_pagamento' => $pagamento->status,
            'id_nota_fiscal' => $notaFiscal->id,
        ], 200);

    }
    }
}
