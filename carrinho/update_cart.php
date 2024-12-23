<?php
session_start();
header('Content-Type: application/json'); // Define o cabeçalho como JSON para a resposta



if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Faça login para adicionar ao cesto.'
    ]);
    exit;
}


$userId = $_SESSION['user_id'];


$data = json_decode(file_get_contents('php://input'), true);

// Obtém os parâmetros enviados pelo cliente
$productId = $data['productId'] ?? null; // ID do produto
$size = $data['size'] ?? null; // Tamanho do produto
$quantity = $data['quantity'] ?? null; // Quantidade do produto

// Verifica se todos os dados obrigatórios estão presentes
if (!$productId || !$size || !$quantity || $quantity <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos.'
    ]);
    exit;
}


$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se o produto já está no carrinho com o mesmo tamanho
    $stmt = $pdo->prepare("
        SELECT id 
        FROM cart 
        WHERE user_id = :user_id AND product_id = :product_id AND size = :size
    ");
    $stmt->execute([
        'user_id' => $userId,
        'product_id' => $productId,
        'size' => $size
    ]);
    $cartItem = $stmt->fetch(); // Verifica se o produto já existe no carrinho

    if ($cartItem) {
        // Atualiza a quantidade do produto existente no carrinho
        $stmt = $pdo->prepare("
            UPDATE cart 
            SET quantity = quantity + :quantity 
            WHERE id = :id
        ");
        $stmt->execute([
            'quantity' => $quantity, // Soma a nova quantidade à existente
            'id' => $cartItem['id'] // Referência ao ID do produto no carrinho
        ]);
    } else {
        // Insere um novo produto no carrinho
        $stmt = $pdo->prepare("
            INSERT INTO cart (user_id, product_id, size, quantity) 
            VALUES (:user_id, :product_id, :size, :quantity)
        ");
        $stmt->execute([
            'user_id' => $userId, // ID do utilizador logado
            'product_id' => $productId, // ID do produto adicionado
            'size' => $size, // Tamanho do produto
            'quantity' => $quantity // Quantidade do produto
        ]);
    }


    echo json_encode([
        'success' => true,
        'message' => 'Produto adicionado ao cesto com sucesso!'
    ]);
} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Erro ao processar sua solicitação: ' . $e->getMessage()
    ]);
}
