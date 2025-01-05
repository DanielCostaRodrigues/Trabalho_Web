<?php
session_start();


header('Content-Type: application/json');


if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'success' => false,
        'count' => 0,
        'message' => 'Utilizador não autenticado'
    ]);
    exit;
}

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


try {

    // Consulta para calcular a quantidade total de produtos no carrinho do user
    $stmt = $pdo->prepare('SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $_SESSION['user_id']]); // Substitui o parâmetro :user_id pelo ID do user logado
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém o resultado como um array associativo

    // Total de produtos no carrinho
    $count = $result['total_quantity'] ?? 0; // Se não houver produtos no carrinho define o total como 0

    // Retorna uma resposta JSON com sucesso
    echo json_encode([
        'success' => true,
        'count' => $count
    ]);
} catch (PDOException $e) {
    // Se ocorrer um erro na bd retorna uma mensagem de erro em formato JSON
    echo json_encode([
        'success' => false,
        'count' => 0,
        'message' => 'Erro ao conectar ao banco de dados'
    ]);
}
