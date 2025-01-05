<?php
// Obtém o filtro da URL ou define "Todos" como padrão
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Todos';


require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


// Selecionar apenas produtos ativos da categoria fem (category_id = 2)
$sql = "SELECT * FROM products WHERE category_id = 2 AND active = 1"; // Seleciona apenas produtos ativos e fem
if ($filter !== 'Todos') {
    $sql .= " AND sub_category = :filter"; // Adiciona um filtro de subcategoria se necessário
}

$stmt = $pdo->prepare($sql); // Prepara a consulta SQL
$params = []; // Parâmetros para a consulta
if ($filter !== 'Todos') {
    $params['filter'] = $filter; // Define o valor do filtro
}
$stmt->execute($params); // Executa a consulta com os parâmetros
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Busca todos os produtos encontrados
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Coleção Feminina - Origens Lusas</title>
</head>

<body class="genre">
    <?php
    $isFemalePage = true; // Indica que a página atual é da coleção feminina
    include('../includes/header.php');
    ?>

    <div class="main-container">
        <main>
            <div class="collection-section">
                <h1 class="section-title">Coleção Feminina</h1>
                <p>Exibindo produtos: <strong><?= htmlspecialchars($filter) ?></strong></p> <!-- Exibe o filtro aplicado -->

                <!-- Exibir os produtos -->
                <section class="products">
                    <?php if (!empty($produtos)): ?> <!-- Verifica se há produtos para exibir -->
                        <?php foreach ($produtos as $produto): ?>
                            <div class="product">
                                <img src="<?= htmlspecialchars($produto['image']) ?>" alt="<?= htmlspecialchars($produto['name']) ?>"> <!-- Imagem do produto -->
                                <h2><?= htmlspecialchars($produto['name']) ?></h2> <!-- Nome do produto -->
                                <p>€<?= number_format($produto['price'], 2, ',', '.') ?></p> <!-- Preço do produto formatado -->
                                <button onclick="location.href='../detalhe_produtos/product_detail.php?id=<?= $produto['id'] ?>&tipo=feminino'">Comprar</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?> <!-- Caso nenhum produto seja encontrado -->
                        <p>Nenhum produto encontrado para o filtro selecionado.</p>
                    <?php endif; ?>
                </section>
            </div>
        </main>

        <!-- Barra lateral com filtros -->
        <aside class="sidebar">
            <h2>Filtros</h2>

            <button class="filter-btn" onclick="location.href='female.php?filter=Parte%20Superior'">Parte Superior</button>
            <button class="filter-btn" onclick="location.href='female.php?filter=Parte%20Inferior'">Parte Inferior</button>
            <button class="filter-btn" onclick="location.href='female.php?filter=Exterior'">Exterior</button>
            <button class="filter-btn" onclick="location.href='female.php?filter=Todos'">Todos os Produtos</button>
        </aside>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>