<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados


$categoryFilter = isset($_GET['category']) ? $_GET['category'] : 'all';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

try {

    if (isset($_GET['action'], $_GET['id']) && in_array($_GET['action'], ['activate', 'deactivate'])) {
        $productId = $_GET['id'];
        $newStatus = $_GET['action'] === 'activate' ? 1 : 0;

        $stmt = $pdo->prepare('UPDATE products SET active = :active WHERE id = :id');
        $stmt->execute(['active' => $newStatus, 'id' => $productId]);

        header('Location: manage_products.php');
        exit;
    }

    $query = 'SELECT * FROM products WHERE 1';
    $params = [];

    if ($categoryFilter === 'male') {
        $query .= ' AND category_id = 1';
    } elseif ($categoryFilter === 'female') {
        $query .= ' AND category_id = 2';
    }

    if (!empty($searchQuery)) {
        $query .= ' AND name LIKE :search';
        $params['search'] = '%' . $searchQuery . '%';
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Produtos</title>
    <link rel="stylesheet" href="../css/admin_perfil.css">
</head>

<body class="manage-products">
    <h1>Gerir Produtos</h1>

    <div class="search-container">
        <form method="GET" action="manage_products.php">
            <input type="text" name="search" placeholder="Pesquisar produtos..." value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit">Pesquisar</button>
        </form>
    </div>

    <div class="filter-container">
        <form method="GET" action="manage_products.php">
            <select name="category">
                <option value="all" <?= $categoryFilter === 'all' ? 'selected' : '' ?>>Todos os Produtos</option>
                <option value="male" <?= $categoryFilter === 'male' ? 'selected' : '' ?>>Produtos Masculinos</option>
                <option value="female" <?= $categoryFilter === 'female' ? 'selected' : '' ?>>Produtos Femininos</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Preço</th>
                <th>Stock</th>
                <th>Tamanhos</th>
                <th>Subcategoria</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>€<?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td><?php echo htmlspecialchars($product['sizes']); ?></td>
                    <td><?php echo htmlspecialchars($product['sub_category']); ?></td>
                    <td>
                        <?php if ($product['active']): ?>
                            <span class="status-active">Ativo</span>
                        <?php else: ?>
                            <span class="status-inactive">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>">Editar</a> |
                        <?php if ($product['active']): ?>
                            <a href="manage_products.php?action=deactivate&id=<?php echo $product['id']; ?>"
                                onclick="return confirm('Tem certeza que deseja desativar este produto? Ele não aparecerá mais no site.');">Desativar</a>
                        <?php else: ?>
                            <a href="manage_products.php?action=activate&id=<?php echo $product['id']; ?>"
                                onclick="return confirm('Tem certeza que deseja ativar este produto? Ele ficará visível no site.');">Ativar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="admin-actions">
        <a href="add_product.php">Adicionar Produto</a>
        <a href="admin_dashboard.php">Voltar</a>
    </div>
</body>

</html>
