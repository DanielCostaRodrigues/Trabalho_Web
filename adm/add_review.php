<?php
session_start();

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


// Verifica user logado
if (!isset($_SESSION['user_id'])) {
    // Se o user não estiver logado, retorna uma mensagem de erro em formato JSON e encerra o script.
    echo json_encode(['success' => false, 'message' => 'Você precisa estar logado para adicionar um comentário.']);
    exit;
}

// Captura os dados enviados
$productId = $_POST['product_id'] ?? null; // Captura o ID do produto enviado via POST, ou `null` se não existir.
$comment = trim($_POST['comment'] ?? ''); // Captura o comentário enviado via POST e remove espaços extras.
$userId = $_SESSION['user_id']; // Obtém o ID do user logado.

if (!$productId || empty($comment)) {
    // Verifica se o ID do produto ou o comentário estão ausentes e retorna uma mensagem de erro.
    echo json_encode(['success' => false, 'message' => 'Comentário ou produto inválido.']);
    exit;
}

try {

    // Inserir o comentário na bd
    $stmt = $pdo->prepare("INSERT INTO product_reviews (product_id, user_id, comment, created_at) 
                           VALUES (:product_id, :user_id, :comment, NOW())");
    $stmt->execute([
        'product_id' => $productId, // Vincula o ID do produto ao placeholder :product_id
        'user_id' => $userId,       // Vincula o ID do user ao placeholder :user_id
        'comment' => $comment       // Vincula o comentário ao placeholder :comment
    ]);

    // Retorna uma mensagem de sucesso em formato JSON
    echo json_encode(['success' => true, 'message' => 'Comentário adicionado com sucesso.']);
} catch (PDOException $e) {
    // Captura erros da bd e retorna uma mensagem de erro em formato JSON
    echo json_encode(['success' => false, 'message' => 'Erro ao adicionar comentário: ' . $e->getMessage()]);
}
