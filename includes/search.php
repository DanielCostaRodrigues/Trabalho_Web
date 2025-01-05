<?php

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

// Obtém os parâmetros de pesquisa da URL
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : ''; // Texto da pesquisa
$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null; // ID da categoria 

// SQL base para buscar produtos
$sql = "SELECT * FROM products WHERE (name LIKE :search OR description LIKE :search)"; // Busca por nome ou descrição

// Parâmetros para a consulta
$params = ['search' => '%' . $searchQuery . '%']; // Adiciona o parâmetro de busca

// Adiciona a filtragem por categoria se o parâmetro for fornecido
if ($categoryId) {
    $sql .= " AND category_id = :category_id"; // Adiciona o filtro no SQL
    $params['category_id'] = $categoryId; // Adiciona o valor ao array de parâmetros
}

// Executa a consulta com os parâmetros definidos
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém os resultados como um array associativo
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa - Origens Lusas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="genre">

    <?php include('../includes/header.php'); ?>

    <main>

        <div class="collection-section">
            <h1 class="section-title">Resultados da Pesquisa</h1>
            <p>Resultados para: <strong><?= htmlspecialchars($searchQuery) ?></strong></p> <!-- Exibe o termo pesquisado -->

            <!-- Seção de produtos -->
            <section class="products">
                <?php if (count($produtos) > 0): ?> <!-- Verifica se há produtos -->
                    <?php foreach ($produtos as $produto): ?>
                        <div class="product">
                            <!-- Exibe a imagem do produto -->
                            <img src="<?= htmlspecialchars($produto['image']) ?>" alt="<?= htmlspecialchars($produto['name']) ?>">
                            <!-- Exibe o nome do produto -->
                            <h2><?= htmlspecialchars($produto['name']) ?></h2>
                            <!-- Exibe o preço do produto -->
                            <p>€<?= htmlspecialchars($produto['price']) ?></p>
                            <!-- Botão para acessar detalhes do produto -->
                            <button onclick="location.href='../detalhe_produtos/product_detail.php?id=<?= $produto['id'] ?>'">Comprar</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?> <!-- Caso nenhum produto seja encontrado -->
                    <p>Nenhum produto encontrado para "<strong><?= htmlspecialchars($searchQuery) ?></strong>".</p>
                <?php endif; ?>
            </section>
        </div>
    </main>


    <?php include('../includes/footer.php'); ?>
</body>

</html>