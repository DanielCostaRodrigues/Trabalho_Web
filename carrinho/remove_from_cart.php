<?php
session_start();


if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Utilizador não autenticado'
    ]);
    exit;
}

// Decodifica os dados enviados pelo cliente (JSON no corpo da requisição)
$data = json_decode(file_get_contents('php://input'), true);

// Obtém o ID do produto no carrinho e a quantidade a ser removida
$cartId = $data['cartId'] ?? null;
$quantityToRemove = $data['quantity'] ?? 0;

// Validação dos dados fornecidos
if (!$cartId || $quantityToRemove <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos fornecidos.'
    ]);
    exit;
}


$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica a quantidade atual do produto no carrinho
    $stmt = $pdo->prepare('SELECT quantity FROM cart WHERE id = :id AND user_id = :user_id');
    $stmt->execute([
        'id' => $cartId,
        'user_id' => $_SESSION['user_id']
    ]);
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do produto do carrinho

    if (!$cartItem) {
        // Caso o produto não seja encontrado retorna um erro
        echo json_encode([
            'success' => false,
            'message' => 'Item não encontrado ou já removido.'
        ]);
        exit;
    }

    $currentQuantity = $cartItem['quantity']; // Quantidade atual do produto no carrinho

    if ($quantityToRemove >= $currentQuantity) {
        // Se a quantidade a remover for maior ou igual à quantidade atual remove o produto completamente
        $deleteStmt = $pdo->prepare('DELETE FROM cart WHERE id = :id AND user_id = :user_id');
        $deleteStmt->execute([
            'id' => $cartId,
            'user_id' => $_SESSION['user_id']
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Item removido do carrinho.'
        ]);
    } else {
        // Caso contrário atualiza a quantidade no carrinho
        $updateStmt = $pdo->prepare('UPDATE cart SET quantity = quantity - :quantity WHERE id = :id AND user_id = :user_id');
        $updateStmt->execute([
            'quantity' => $quantityToRemove,
            'id' => $cartId,
            'user_id' => $_SESSION['user_id']
        ]);

        echo json_encode([
            'success' => true,
            'message' => 'Quantidade atualizada no carrinho.'
        ]);
    }
} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Erro ao remover o item: ' . $e->getMessage()
    ]);
}
