<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $newAddress = $_POST['address'];
    //$newAddress = htmlspecialchars(trim($_POST['address']));


    require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


    try {


        // Atualiza a morada do user na bd
        $stmt = $pdo->prepare('UPDATE users SET address = :address WHERE id = :id');
        $stmt->execute([
            'address' => $newAddress, // Define o valor da nova morada
            'id' => $userId           // Define o ID do user
        ]);

        // Redireciona para a página de perfil após a atualização
        header('Location: profile.php');
        exit;
    } catch (PDOException $e) {
        // Em caso de erro na execução da query, exibe uma mensagem de erro
        die("Erro ao atualizar a morada: " . $e->getMessage());
    }
}
