<?php
declare(strict_types=1);

//importa os arquivos para funcionar
require_once 'receita.php';
require_once 'despesa.php';

class carteira
{
    private float $saldo = 0; //guarda o saldo
    private array $transacoes = []; //aguarda a lista de transacoes

    public function adicionarTransacao(transacao $transacao): void //adiciona uma transicao
    {
        if ($transacao instanceof Receita) {
            $this->saldo += $transacao->getValor(); //se for receita, aumenta o saldo
        }

        if ($transacao instanceof Despesa) {

            if ($transacao->getValor() > $this->saldo) { //nao deixa o saldo ficar negativo
                throw new Exception(
                    "Saldo insuficiente para realizar esta despesa."
                );
            }

            $this->saldo -= $transacao->getValor(); //se for despesa, diminui o saldo
        }

        $this->transacoes[] = $transacao; //guarda a transacao
    }

    public function getSaldo(): float 
    {
        return $this->saldo; //retorna o saldo
    }

    public function getTransacoes(): array
    {
        return $this->transacoes; //retorna a lista de transacao
    }
}