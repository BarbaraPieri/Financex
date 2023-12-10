<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pagamento;

class PagamentoConfirmado extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $pagamento;

    /**
     * Create a new message instance.
     *
     * @param Pagamento $pagamento
     */
    public function __construct(Pagamento $pagamento)
    {
        $this->pagamento = $pagamento;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
{
    return $this->subject('Pagamento Confirmado')
                ->to($this->pagamento->email)
                ->view('emails.pagamento-confirmado');
}

}
