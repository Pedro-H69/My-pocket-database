<?php
session_start();

require_once 'conexao.php';
require_once 'carteira.php';
require_once 'receita.php';
require_once 'despesa.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

// Busca a transação no banco de dados
$sql = "SELECT * FROM transacoes WHERE id = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$transacaoBD = $resultado->fetch_assoc();

if (!$transacaoBD) {
    $_SESSION['mensagem'] = "Transação não encontrada!";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Transação - MyPocket</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Transação</h1>

        <form action="atualiza.php" method="POST">
            <input type="hidden" name="id" value="<?= $transacaoBD['id'] ?>">

            <label>Descrição:</label>
            <input class="campo" type="text" name="descricao" value="<?= htmlspecialchars($transacaoBD['descricao']) ?>" required>

            <label>Valor:</label>
            <input class="campo" type="number" step="0.01" name="valor" value="<?= $transacaoBD['valor'] ?>" required>

            <button class="botao" type="submit">Atualizar</button>
            <a href="index.php" style="margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</body>
</html>