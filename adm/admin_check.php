<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: ../login/login.php');
    exit;
}

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


try {
    // Verificar se o user é admin
    $stmt = $pdo->prepare('SELECT id, is_admin FROM users WHERE id = :id'); // Prepara a query para buscar o user.
    $stmt->execute(['id' => $_SESSION['user_id']]); // Executa a query com o ID do user logado.
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do user como um array associativo.

    if (!$user) {
        // Se o user não for encontrado na bd:
        session_destroy(); // Destroi a sessão atual.
        header('Location: ../login/login.php'); // Redireciona para a página de login.
        exit;
    }

    // Atualizar o valor da sessão para garantir que está sincronizado
    $_SESSION['is_admin'] = $user['is_admin']; // Atualiza a variável de sessão `is_admin` com o valor da bd.

    if ($_SESSION['is_admin'] != 1) {
        // Verifica se o user não é administrador.
        // Caso não seja, redireciona para a página inicial do site.
        header('Location: ../index.php');
        exit;
    }
} catch (PDOException $e) {
    // Captura erros de conexão ou execução de query.
    die('Erro ao conectar com a base de dados: ' . $e->getMessage()); // Exibe a mensagem de erro e encerra o script.
}
