<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

try {



    // Processar ações (adicionar editar remover)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['action'])) { // Verifica qual ação foi enviada
            if ($_POST['action'] === 'add' && !empty($_POST['category_name'])) {
                // Adicionar uma nova categori.
                $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (:name)');
                $stmt->execute(['name' => $_POST['category_name']]);
            } elseif ($_POST['action'] === 'edit' && !empty($_POST['category_id']) && !empty($_POST['category_name'])) {
                // Editar uma categoria que existe
                $stmt = $pdo->prepare('UPDATE categories SET name = :name WHERE id = :id');
                $stmt->execute(['name' => $_POST['category_name'], 'id' => $_POST['category_id']]);
            } elseif ($_POST['action'] === 'delete' && !empty($_POST['category_id'])) {
                // Remove uma categoria
                $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
                $stmt->execute(['id' => $_POST['category_id']]);
            }
        }
        header('Location: manage_categories.php');
        exit;
    }

    // Obter categorias
    $stmt = $pdo->query('SELECT * FROM categories'); // Vai buscar todas as categorias na bd
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC); // Guarda as categorias como um array associativo
} catch (PDOException $e) {
    // Exibe mensagem de erro caso ocorra um problema na conexão.
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Categorias</title>
    <link rel="stylesheet" href="../css/admin_perfil.css">

<body class="manage-categories">
    <h1>Gerir Categorias</h1>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category['id']; ?></td> <!-- ID categoria -->
                    <td><?php echo htmlspecialchars($category['name']); ?></td> <!-- Nome da categoria. -->
                    <td>
                        <!-- Formulário para editar uma categoria -->
                        <form method="POST" class="form-categories">
                            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>"> <!-- ID oculto da categoria -->
                            <input type="text" name="category_name" placeholder="Editar Nome" value="<?php echo htmlspecialchars($category['name']); ?>" required>
                            <button type="submit" name="action" value="edit">Editar</button>
                        </form>

                        <!-- Formulário para remover uma categoria -->
                        <form method="POST" class="form-categories-button" onsubmit="return confirm('Tem certeza que deseja remover esta categoria?');">
                            <input type="hidden" name="category_id" value="<?php echo $category['id']; ?>"> <!-- ID oculto da categoria -->
                            <button type="submit" class="delete-btn-manage-categories" name="action" value="delete">Remover</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulário para adicionar uma nova categoria -->
    <div class="admin-actions-manage-categories">
        <form method="POST" style="display: flex; gap: 10px;">
            <input type="text" name="category_name" placeholder="Nova Categoria" required>
            <button type="submit" name="action" value="add">Adicionar Categoria</button>
        </form>
        <button onclick="window.location.href='admin_dashboard.php';">Voltar</button>
    </div>
</body>

</html>