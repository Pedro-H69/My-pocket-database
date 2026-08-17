<?php
session_start(); // inicia a sessão pra guardar os dados da carteira

require_once 'carteira.php'; //importa a carteira

if (!isset($_SESSION['carteira'])) { //ve se ja tem uma carteira na sessao
    $_SESSION['carteira'] = serialize(new carteira()); //se nao iver, cria uma e guarda na sessao, e a guarda como string
}

$carteira = unserialize($_SESSION['carteira']); //recupera a carteira da sessao, e a carteira volta a ser objeto
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Pw-projeto</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h1>MyPocket</h1>

        <h2> <!-- mostra o saldo-->
            Saldo: 
            R$ <?= number_format( //formata o numero pra mostrar como dinheiro
                $carteira->getSaldo(), 
                2, //casas decimais
                ',', //separa decimal com virgula
                '.' //separa milhar com ponto
            ) ?>
        </h2>

        <?php if (isset($_SESSION['mensagem'])): ?> <!--mostra mensagem de sucesso ou erro-->

            <div class="mensagem">
                <?= $_SESSION['mensagem'] ?>
            </div>

            <?php unset($_SESSION['mensagem']); ?>
        <?php endif; ?>

        <!--formulario para transacoes-->
        <form action="processa.php" method="POST">

            <!--Descricao, define texto, envia o nome atraves do post-->
            <input class="campo" type="text" name="descricao" placeholder="Descrição" required>

            <!--Valor, define numero, casa decimal 0.01, envia o nome atraves do post-->
            <input class="campo" type="number" step="0.01" name="valor" placeholder="Valor" required>

            <!-- seleciona o tipo da transacao-->
            <select class="campo" name="tipo">
                <option value="receita">Receita</option>
                <option value="despesa">Despesa</option>
            </select>

            <button class="botao" type="submit">
                Salvar
            </button>

        </form>

        <h2>Extrato</h2>

        <table>

            <!--cabecalho da tabela-->
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Tipo</th>
                <th>Valor</th>
            </tr>

        <!--percorre todo array das transacoes -->
            <?php foreach ($carteira->getTransacoes() as $t): ?>

                <tr>

                    <td><?= $t->getData() ?></td>

                    <td><?= $t->getDescricao() ?></td>

                    <td><?= $t->getTipo() ?></td>

                    <td class="<?= $t->getTipo() === 'Entrada'
                        ? 'entrada'
                        : 'saida' ?>">

                        <!-- formata o valor como dinheiro -->
                        R$ <?= number_format(
                            $t->getValor(),
                            2,
                            ',',
                            '.'
                        ) ?>

                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>

</body>

</html>