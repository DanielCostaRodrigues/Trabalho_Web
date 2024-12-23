<?php
session_start(); // Inicia a sessão para verificar a autenticação do user.

// Verificar se o user é admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    // Manda para a página inicial se o user não estiver logado ou não for admin.
    header('Location: index.php');
    exit;
}

// Conexão com a base de dados
$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4'; // Dados de conexão.
$db_user = 'web'; // Nome do user na bd.
$db_password = 'web'; // passe da bd.

try {
    // Cria uma nova conexão PDO com a bd.
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Define o modo de erro como exceção(verifica erros).

    // Obter categorias disponíveis
    $stmt = $pdo->query('SELECT * FROM categories'); // Busca todas as categorias na bd.
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC); // Armazena o resultado como um array associativo.

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recebe os dados do formulário.
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];
        $sizes = $_POST['sizes']; // Novos tamanhos
        $sub_category = $_POST['sub_category']; // Subcategoria adicionada
        $active = isset($_POST['active']) ? 1 : 0; // Verifica se o produto está ativo.

        // Processar upload da imagem
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "../images/"; // Diretório onde as imagens serão armazenadas.

            // Verifica se o diretório existe, senão cria
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true); // Cria o diretório com permissões adequadas
            }

            $targetFile = $targetDir . basename($_FILES["image"]["name"]); // Caminho completo do arquivo
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION)); // Tipo do arquivo

            // Verifica se o arquivo é uma imagem
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                die("O arquivo enviado não é uma imagem.");
            }

            // Mover a imagem para a pasta de imagens
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                die("Erro ao fazer upload da imagem.");
            }
        } else {
            $targetFile = null; // Sem imagem enviada.
        }

        // Inserir o produto na bd
        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock, description, category_id, sizes, active, image, sub_category) 
                               VALUES (:name, :price, :stock, :description, :category_id, :sizes, :active, :image, :sub_category)');
        $stmt->execute([
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
            'description' => $description,
            'category_id' => $category_id,
            'sizes' => $sizes, // Adicionar tamanhos
            'active' => $active,
            'image' => $targetFile,
            'sub_category' => $sub_category // Adiciona a subcategoria
        ]);

        // Redirecionar para a página de gerir produtos
        header('Location: manage_products.php');
        exit;
    }
} catch (PDOException $e) {
    // Captura erros de conexão ou execução da bd.
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
    <link rel="stylesheet" href="../css/admin_perfil.css"> <!-- Estilo CSS -->
</head>

<body class="add-product">
    <div class="form-container-add-product">
        <h2>Adicionar Produto</h2>
        <form action="" method="POST" enctype="multipart/form-data"> <!-- Formulário para adicionar produtos -->

            <label for="name">Nome do Produto:</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Preço (€):</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>

            <label for="sizes">Tamanhos (separados por vírgulas, ex: XS,S,M,L,XL,XXL):</label>
            <input type="text" id="sizes" name="sizes" placeholder="Exemplo: XS,S,M,L,XL,XXL">

            <label for="description">Descrição:</label>
            <textarea id="description" name="description" required></textarea>

            <!-- Seleção de categoria -->
            <label for="category_id">Categoria:</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="sub_category">Subcategoria:</label>
            <select id="sub_category" name="sub_category" required>
                <option value="Parte Superior">Parte Superior</option>
                <option value="Parte Inferior">Parte Inferior</option>
                <option value="Exterior">Exterior</option>
            </select>

            <label for="image">Imagem do Produto:</label>
            <input type="file" id="image" name="image" accept="image/*">
            <!-- Checkbox para ativar ou desativar o produto -->
            <label>
                <input type="checkbox" name="active"> Ativo
            </label>
            <button type="submit">Adicionar Produto</button>
        </form>
    </div>

    <div class="back-button-add-product">
        <button onclick="window.location.href='manage_products.php';">Voltar</button>
    </div>
</body>

</html>
