<?php
// Obtém o filtro da URL ou define "Todos" como padrão
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'Todos';


require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


// Selecionar apenas produtos ativos da categoria masculina (category_id = 1)
$sql = "SELECT * FROM products WHERE category_id = 1 AND active = 1"; // Consulta base para produtos ativos e masculinos
if ($filter !== 'Todos') {
    $sql .= " AND sub_category = :filter";
}

$stmt = $pdo->prepare($sql);
$params = [];
if ($filter !== 'Todos') {
    $params['filter'] = $filter;
}
$stmt->execute($params); // Executa a consulta com os parâmetros fornecidos
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Busca todos os resultados encontrados
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Coleção Masculina - Origens Lusas</title>
</head>

<body class="genre">
    <?php
    $isMalePage = true; // Define uma variável para identificar a página masculina 
    include('../includes/header.php');
    ?>


    <div class="main-container">
        <main>
            <div class="collection-section">
                <h1 class="section-title">Coleção Masculina</h1>
                <p>Exibindo produtos: <strong><?= htmlspecialchars($filter) ?></strong></p> <!-- Exibe o filtro atual -->

                <!-- Seção de produtos -->
                <section class="products">
                    <?php if (!empty($produtos)): ?> <!-- Verifica se há produtos -->
                        <?php foreach ($produtos as $produto): ?>
                            <div class="product">
                                <!-- Exibe a imagem do produto -->
                                <img src="<?= htmlspecialchars($produto['image']) ?>" alt="<?= htmlspecialchars($produto['name']) ?>">
                                <!-- Exibe o nome do produto -->
                                <h2><?= htmlspecialchars($produto['name']) ?></h2>
                                <!-- Exibe o preço do produto formatado -->
                                <p>€<?= number_format($produto['price'], 2, ',', '.') ?></p>
                                <!-- Botão para acessar os detalhes do produto -->
                                <button onclick="location.href='../detalhe_produtos/product_detail.php?id=<?= $produto['id'] ?>&tipo=masculino'">Comprar</button>
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

            <button class="filter-btn" onclick="location.href='male.php?filter=Parte%20Superior'">Parte Superior</button>
            <button class="filter-btn" onclick="location.href='male.php?filter=Parte%20Inferior'">Parte Inferior</button>
            <button class="filter-btn" onclick="location.href='male.php?filter=Exterior'">Exterior</button>
            <button class="filter-btn" onclick="location.href='male.php?filter=Todos'">Todos os Produtos</button>
        </aside>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>