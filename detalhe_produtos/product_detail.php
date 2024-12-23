<?php
session_start();


$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}

// Obtém o ID do produto enviado via GET
$produtoId = isset($_GET['id']) ? (int)$_GET['id'] : null; // Converte o ID para inteiro
$produto = null; // Variável para armazenar os dados do produto

if ($produtoId) {

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $produtoId]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
}

$tamanhosDisponiveis = [];
if ($produto && !empty($produto['sizes'])) {
    // Se os tamanhos estiverem disponíveis separa-os em um array
    $tamanhosDisponiveis = explode(',', $produto['sizes']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Produto</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="product-details">
    <?php include('../includes/header.php'); ?>

    <main>
        <?php if ($produto): ?> <!-- Verifica se o produto foi encontrado -->
            <div id="product-id" style="display:none;"><?= htmlspecialchars($produtoId) ?></div>
            <div class="product-detail">
                <!-- Imagem do Produto -->
                <div class="product-detail-image">
                    <img src="<?= htmlspecialchars($produto['image']) ?>" alt="<?= htmlspecialchars($produto['name']) ?>">
                </div>

                <!-- Informações do Produto -->
                <div class="product-detail-info">
                    <h1><?= htmlspecialchars($produto['name']) ?></h1> <!-- Nome do produto -->
                    <p>€<?= number_format($produto['price'], 2, ',', '.') ?></p> <!-- Preço do produto -->
                    <p><?= htmlspecialchars($produto['description']) ?></p> <!-- Descrição do produto -->

                    <!-- Controle de stock -->
                    <?php if ($produto['stock'] <= 10 && $produto['stock'] > 0): ?>
                        <p class="low-stock">Apenas <?= $produto['stock'] ?> Unidades restantes em stock!</p>
                    <?php elseif ($produto['stock'] == 0): ?>
                        <p class="low-stock">Stock esgotado!</p>
                    <?php endif; ?>

                    <!-- Opções de Tamanho -->
                    <div class="size-options">
                        <span>Tamanho:</span>
                        <?php foreach ($tamanhosDisponiveis as $tamanho): ?>
                            <button type="button" class="size-button" onclick="selectSize(this)">
                                <?= htmlspecialchars($tamanho) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>


                    <button
                        id="add-to-cart-button"
                        onclick="addToCart()"
                        <?= $produto['stock'] == 0 ? 'disabled' : '' ?>> <!-- Desabilita se o produto estiver esgotado -->
                        Adicionar ao Cesto
                    </button>
                </div>
            </div>

            <!-- Seção de Comentários -->
            <div class="comments-section">
                <h2>Comentários</h2>
                <div id="comments-list"></div>
                <div id="pagination"></div>

                <!-- Formulário de Adicionar Comentário -->
                <?php if (isset($_SESSION['user_id'])): ?> <!-- Verifica se o utilizador está autenticado -->
                    <form id="add-comment-form" class="add-comment-form">
                        <textarea id="comment" placeholder="Escreva seu comentário..." required></textarea>
                        <button type="submit">Adicionar Comentário</button>
                    </form>
                <?php else: ?>
                    <p>Faça login para adicionar um comentário.</p>
                <?php endif; ?>
            </div>
        <?php else: ?> <!-- Caso o produto não seja encontrado -->
            <p>Produto não encontrado.</p>
        <?php endif; ?>
    </main>

    <?php include('../includes/footer.php'); ?>

    <script src="../script/script.js"></script>
</body>

</html>