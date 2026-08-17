<?php
declare(strict_types=1);

require_once 'transacao.php'; //importa aclasse pra ter a heranca

class receita extends transacao
{
    public function getTipo(): string
    {
        return "Entrada"; //retorna o tipo da transacao
    }
}