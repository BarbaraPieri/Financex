<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PagamentoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Rotas do PagamentoController
Route::apiResource('pagamentos', PagamentoController::class)->except([
    'create', 'edit'
]);

Route::delete('/pagamentos/{pagamento}', [PagamentoController::class, 'destroy'])
    ->name('pagamentos.cancelarPagamento');
Route::patch('/pagamentos/{pagamento}', [PagamentoController::class, 'update'])
    ->name('pagamentos.confirmarPagamento');

Route::get('/pagamentos/{pagamento}/mail', [PagamentoController::class, 'mailConfirmationEmail'])
->name('pagamentos.mailConfirmationEmail');
