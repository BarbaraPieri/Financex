<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pagamento;
use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function cancelarPagamento($id)
    {
        // Encontrar e cancelar um pagamento pelo ID
        $pagamento = Pagamento::find($id);

        if (!$pagamento) {
            return response()->json(['error' => 'Pagamento não encontrado'], 404);
        }

        $pagamento->update(['status' => 'CANCELADO']);

        return response()->json([], 204);
    }

    public function confirmarPagamento(Request $request, $id)
    {
        // Encontrar um pagamento pelo ID
        $pagamento = Pagamento::find($id);

        if (!$pagamento) {
            return response()->json(['error' => 'Pagamento não encontrado'], 404);
        }

        // Validação dos dados do cliente
        $request->validate([
            'nome' => 'required',
            'email' => 'required|email',
            'cpf' => 'required|digits:11|numeric',
            'telefone' => 'required',
            'rua' => 'required',
            'numero' => 'required|numeric',
            'bairro' => 'required',
            'cidade' => 'required',
            'estado' => 'required',
        ]);

        // Criação da nota fiscal associada ao pagamento
        $notaFiscal = new NotaFiscal([
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

        $notaFiscal->save();

        // Associa a nota fiscal ao pagamento
        $pagamento->notaFiscal()->associate($notaFiscal);
        $pagamento->update(['status' => 'CONFIRMADO']);

        return response()->json([
            'id_pagamento' => $pagamento->id,
            'status_pagamento' => $pagamento->status,
            'id_nota_fiscal' => $notaFiscal->id,
        ], 200);
    }
}

