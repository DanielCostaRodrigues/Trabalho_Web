<?php

header('Content-Type: application/json');

require_once 'includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

// Obtém os parâmetros enviados via GET
$categoryId = $_GET['category_id'] ?? null; // ID da categoria (obrigatório)
$subCategory = $_GET['sub_category'] ?? null; // Subcategoria (opcional)

try {

    // Consulta SQL base para buscar produtos filtrados por categoria
    $sql = "SELECT * FROM products WHERE category_id = :category_id";
    $params = ['category_id' => $categoryId]; // Parâmetros para a consulta

    // Adiciona filtro de subcategoria se fornecido
    if ($subCategory && $subCategory !== 'Todos') {
        $sql .= " AND sub_category = :sub_category";
        $params['sub_category'] = $subCategory; // Adiciona o filtro de subcategoria
    }

    // Prepara e executa a consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Busca os resultados e os retorna como JSON
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products); // Retorna os produtos em formato JSON
} catch (PDOException $e) {
    // Em caso de erro retorna uma mensagem de erro em formato JSON
    echo json_encode(['error' => 'Erro ao buscar produtos: ' . $e->getMessage()]);
}
