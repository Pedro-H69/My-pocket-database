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


    // Adiciona a transação à carteira
    // Aqui também acontece a validação do saldo
    $carteira->adicionarTransacao($transacao);


    // Prepara o comando SQL para inserir a transação
    $sql = "INSERT INTO transacoes
        (tipo, descricao, valor, data_transacao)
        VALUES (?, ?, ?, ?)";


    // Prepara a consulta
    $stmt = $conexao->prepare($sql);


    // Define os valores que serão enviados para o banco
    $stmt->bind_param(
        "ssds",
        $tipo,
        $descricao,
        $valor,
        $data
    );


    // Executa o INSERT no banco de dados
    $stmt->execute();

    //pega o id gerado pelo banco de dados para a transacao inserida
    $idgerado = $conexao->insert_id;
    $transacao->setId($idgerado);

    // Fecha o comando
    $stmt->close();

    $_SESSION['carteira'] = serialize($carteira);

    // Mensagem de sucesso
    $_SESSION['mensagem'] = "Transação cadastrada com sucesso!";


} catch (Exception $e) {

    // Caso aconteça algum erro, mostra a mensagem
    $_SESSION['mensagem'] = $e->getMessage();
}


// Volta para a página inicial
header("Location: index.php");

exit;