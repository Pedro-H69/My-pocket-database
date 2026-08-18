<?php
session_start();

require_once 'conexao.php';
require_once 'carteira.php';
require_once 'receita.php';
require_once 'despesa.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $descricao = $_POST['descricao'];
    $valor = (float)$_POST['valor'];

    try {
        // atualiza no Banco de Dados
        $sql = "UPDATE transacoes SET descricao = ?, valor = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sdi", $descricao, $valor, $id);
        $stmt->execute();
        $stmt->close();

        // atualiza a carteira/sessão atualizando no Banco
        // garantir que o saldo estejacorreto
        $novaCarteira = new carteira();
        
        $sqlBusca = "SELECT * FROM transacoes ORDER BY id ASC";
        $res = $conexao->query($sqlBusca);

        while ($row = $res->fetch_assoc()) {
            if ($row['tipo'] === 'receita') {
                $t = new receita((float)$row['valor'], $row['descricao'], $row['data_transacao'], (int)$row['id']);
            } else {
                $t = new despesa((float)$row['valor'], $row['descricao'], $row['data_transacao'], (int)$row['id']);
            }
            $novaCarteira->adicionarTransacao($t);
        }

        $_SESSION['carteira'] = serialize($novaCarteira);
        $_SESSION['mensagem'] = "Transação atualizada com sucesso!";

    } catch (Exception $e) {
        $_SESSION['mensagem'] = "Erro ao atualizar: " . $e->getMessage();
    }
}

header("Location: index.php");
exit;