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
    // Verifica se o ID do produto e o comentário foram fornecidos
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos.'
    ]);
    exit;
}


$dsn = 'mysql:host=localhost;dbname=grupo106;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insere o comentário na bd
    $stmt = $pdo->prepare('
        INSERT INTO product_reviews (product_id, user_id, comment, created_at)
        VALUES (:product_id, :user_id, :comment, NOW())
    ');
    $stmt->execute([
        'product_id' => $productId, // ID do produto
        'user_id' => $userId, // ID do user que fez o comentário
        'comment' => $comment // Comentário fornecido
    ]);

    // Retorna uma mensagem de sucesso
    echo json_encode([
        'success' => true,
        'message' => 'Comentário adicionado com sucesso!'
    ]);
} catch (PDOException $e) {
    // Em caso de erro, retorna uma mensagem de erro em JSON
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao adicionar o comentário: ' . $e->getMessage()
    ]);
}
