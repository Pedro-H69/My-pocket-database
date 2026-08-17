<?php

// Inicia a sessão
session_start();

// Importa a conexão com o banco de dados
require_once 'conexao.php';

// Importa a classe Carteira
require_once 'carteira.php';

// Importa as classes de transação
require_once 'receita.php';
require_once 'despesa.php';


// Verifica se já existe uma carteira na sessão
if (!isset($_SESSION['carteira'])) {

    // Cria uma nova carteira
    $_SESSION['carteira'] = serialize(new carteira());
}


try {

    // Pega o tipo enviado pelo formulário
    $tipo = $_POST['tipo'];

    // Pega o valor e transforma em número decimal
    $valor = (float) $_POST['valor'];

    // Pega a descrição da transação
    $descricao = $_POST['descricao'];

    // Pega a data atual no formato aceito pelo MySQL
    $data = date('Y-m-d');


    // Verifica se a transação é uma receita
    if ($tipo === 'receita') {

        // Cria um objeto Receita
        $transacao = new receita(
            $valor,
            $descricao,
            $data
        );

    } else {

        // Cria um objeto Despesa
        $transacao = new despesa(
            $valor,
            $descricao,
            $data
        );
    }

    // Recupera a carteira salva na sessão
    $carteira = unserialize($_SESSION['carteira']);


    // Adiciona a transação à carteira
    // Aqui também acontece a validação do saldo
    $carteira->adicionarTransacao($transacao);


    // ID do cliente que está fazendo a transação
    $cliente_id = 1;


    // Prepara o comando SQL para inserir a transação
    $sql = "INSERT INTO transacoes
            (cliente_id, tipo, descricao, valor, data_transacao)
            VALUES (?, ?, ?, ?, ?)";


    // Prepara a consulta
    $stmt = $conexao->prepare($sql);


    // Define os valores que serão enviados para o banco
    $stmt->bind_param(
        "issds",
        $cliente_id,
        $tipo,
        $descricao,
        $valor,
        $data
    );


    // Executa o INSERT no banco de dados
    $stmt->execute();


    // Fecha o comando
    $stmt->close();


    // Mensagem de sucesso
    $_SESSION['mensagem'] = "Transação cadastrada com sucesso!";


} catch (Exception $e) {

    // Caso aconteça algum erro, mostra a mensagem
    $_SESSION['mensagem'] = $e->getMessage();
}


// Salva novamente a carteira na sessão
$_SESSION['carteira'] = serialize($carteira);


// Volta para a página inicial
header("Location: index.php");

exit;