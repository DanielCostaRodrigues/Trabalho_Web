<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $newAddress = $_POST['address'];

    $dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
    $db_user = 'web';
    $db_password = 'web';

    try {

        $pdo = new PDO($dsn, $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
