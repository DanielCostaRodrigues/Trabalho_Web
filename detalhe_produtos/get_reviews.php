<?php

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


// Obtém os parâmetros enviados via GET
$productId = $_GET['product_id'] ?? null; // ID do produto a ser filtrado
$commentsPerPage = $_GET['comments_per_page'] ?? 5; // Quantidade de comentários por página (5)
$page = $_GET['page'] ?? 1; // Página atual (1)

// Verifica se o ID do produto foi fornecido
if (!$productId) {
    echo json_encode(['error' => 'Produto inválido.']); // Retorna erro caso o produto não seja especificado
    exit;
}

try {
    // Conta o total de comentários para o produto
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_reviews WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $productId]); // Substitui o parâmetro `:product_id`
    $totalComments = $stmt->fetchColumn(); // Obtém o total de comentários

    // Calcula o total de páginas com base no número de comentários por página
    $totalPages = ceil($totalComments / $commentsPerPage); // Divide o total de comentários pelo limite por página

    // Calcula o offset para a consulta com base na página atual
    $offset = ($page - 1) * $commentsPerPage;

    // Busca os comentários do produto com limite e offset
    $stmt = $pdo->prepare("
        SELECT pr.comment, pr.created_at, u.username 
        FROM product_reviews pr 
        JOIN users u ON pr.user_id = u.id
        WHERE pr.product_id = :product_id
        ORDER BY pr.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    // Define os valores dos parâmetros para a consulta
    $stmt->bindValue(':product_id', $productId, PDO::PARAM_INT); // ID do produto
    $stmt->bindValue(':limit', (int)$commentsPerPage, PDO::PARAM_INT); // Limite de comentários por página
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT); // Offset calculado
    $stmt->execute(); // Executa a consulta
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC); // Busca os comentários como um array associativo

    // Retorna os comentários e o total de páginas em formato JSON
    echo json_encode([
        'comments' => $comments,
        'totalPages' => $totalPages
    ]);
} catch (PDOException $e) {
    // Retorna erro em caso de falha na conexão ou execução da consulta
    echo json_encode(['error' => 'Erro ao conectar com a base de dados']);
}
