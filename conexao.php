<?php
declare(strict_types=1);

$host = "localhost";

$usuario = "root";

$senha = "";

$banco = "Mypocket";

// cria a conexão com o banco de dados
$conexao = new mysqli($host, $usuario, $senha, $banco);

// ve se teve algum erro na conexão
if ($conexao->connect_error) {

    // Interrompe o programa e mostra o erro
    die("Erro ao conectar ao banco: " . $conexao->connect_error);
}

// Define UTF-8 para aceitar acentos corretamente
$conexao->set_charset("utf8mb4");
?>