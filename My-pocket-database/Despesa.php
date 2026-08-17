<?php
declare(strict_types=1);

require_once 'transacao.php'; //importa a classe pra ter a heranca

class Despesa extends Transacao
{
    public function getTipo(): string
    {
        return "Saída";  //retorna o tipo da transacao
    }
}