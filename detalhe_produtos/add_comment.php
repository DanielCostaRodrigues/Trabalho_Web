<?php
session_start();
header('Content-Type: application/json');

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Faça login para adicionar um comentário.'
    ]);
    exit;
}

// Obtém o ID do user 
$userId = $_SESSION['user_id'];

// Decodifica os dados enviados pelo cliente 
$data = json_decode(file_get_contents('php://input'), true);

// Extrai os dados do comentário e do produto
$productId = $data['product_id'] ?? null; // ID do produto.
$comment = $data['comment'] ?? null; // Comentário fornecido pelo user

// Validação de dados fornecidos
if (!$productId || !$comment) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos.'
    ]);
    exit;
}

try {
    // Insere o comentário na bd usando a conexão $pdo do db_connection.php
    $stmt = $pdo->prepare('
        INSERT INTO product_reviews (product_id, user_id, comment, created_at)
        VALUES (:product_id, :user_id, :comment, NOW())
    ');
    $stmt->execute([
        'product_id' => $productId, // ID do produto
        'user_id' => $userId, // ID do user que fez o comentário
        'comment' => $comment // Comentário fornecido
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Comentário adicionado com sucesso!'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao adicionar o comentário: ' . $e->getMessage()
    ]);
}
