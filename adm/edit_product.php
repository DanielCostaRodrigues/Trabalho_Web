<?php
session_start();

// Verificar se o user é admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    // Redireciona para a página inicial caso o user não esteja autenticado ou não seja admin
    header('Location: index.php');
    exit;
}

// Conexão com a base de dados
$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {
    // Conecta ao banco de dados
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter informações do produto
    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $_GET['id']]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die("Produto não encontrado.");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $sizes = $_POST['sizes'];
        $sub_category = $_POST['sub_category']; // Subcategoria
        $active = isset($_POST['active']) ? 1 : 0;

        // Upload da imagem se for enviada
        $imagePath = $product['image'];
        if (!empty($_FILES['image']['name'])) {
            $imagePath = '../images/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }

        // Atualização dos dados do produto na base de dados
        $stmt = $pdo->prepare('UPDATE products 
                               SET name = :name, price = :price, stock = :stock, description = :description, 
                                   category_id = :category_id, sizes = :sizes, sub_category = :sub_category, 
                                   active = :active, image = :image 
                               WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
            'description' => $description,
            'category_id' => $category_id,
            'sizes' => $sizes,
            'sub_category' => $sub_category, // Atualiza a subcategoria
            'active' => $active,
            'image' => $imagePath,
            'id' => $_GET['id'],
        ]);

        header('Location: manage_products.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="../css/admin_perfil.css">
</head>
<body>
    <div class="form-container-edit-product">
        <h1>Editar Produto</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

            <label for="price">Preço (€):</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>

            <label for="description">Descrição:</label>
            <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>

            <label for="sizes">Tamanhos (separados por vírgulas, ex: XS,S,M,L,XL,XXL):</label>
            <input type="text" id="sizes" name="sizes" value="<?php echo htmlspecialchars($product['sizes']); ?>" placeholder="Exemplo: XS,S,M,L,XL,XXL">

            <label for="category_id">Categoria:</label>
            <select id="category_id" name="category_id">
                <option value="1" <?php echo $product['category_id'] == 1 ? 'selected' : ''; ?>>Masculino</option>
                <option value="2" <?php echo $product['category_id'] == 2 ? 'selected' : ''; ?>>Feminino</option>
            </select>

            <label for="sub_category">Subcategoria:</label>
            <select id="sub_category" name="sub_category">
                <option value="Parte Superior" <?php echo $product['sub_category'] == 'Parte Superior' ? 'selected' : ''; ?>>Parte Superior</option>
                <option value="Parte Inferior" <?php echo $product['sub_category'] == 'Parte Inferior' ? 'selected' : ''; ?>>Parte Inferior</option>
                <option value="Exterior" <?php echo $product['sub_category'] == 'Exterior' ? 'selected' : ''; ?>>Exterior</option>
            </select>

            <label for="active">Ativo:</label>
            <input type="checkbox" id="active" name="active" <?php echo $product['active'] ? 'checked' : ''; ?>>

            <label for="image">Imagem:</label>
            <input type="file" id="image" name="image">
            <?php if ($product['image']): ?>
                <p>Imagem atual:</p>
                <img src="<?php echo $product['image']; ?>" alt="Imagem do produto">
            <?php endif; ?>

            <button type="submit">Guardar Alterações</button>
        </form>
    </div>
</body>
</html>
