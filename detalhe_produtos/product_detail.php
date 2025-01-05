<?php
session_start();
require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

// Obtém o ID do produto enviado via GET
$produtoId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$produto = null;

// Define o número de comentários por página
$comentariosPorPagina = 5;

// Obtém a página atual (por padrão, página 1)
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

// Calcula o deslocamento para a consulta SQL
$offset = ($paginaAtual - 1) * $comentariosPorPagina;

if ($produtoId) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $produtoId]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
}

$tamanhosDisponiveis = [];
if ($produto && !empty($produto['sizes'])) {
    $tamanhosDisponiveis = explode(',', $produto['sizes']);
}

// Insere o comentário no banco de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $comment = trim($_POST['comment']);
    if ($comment && $produtoId) {
        $stmt = $pdo->prepare("INSERT INTO product_reviews (product_id, user_id, comment) VALUES (:product_id, :user_id, :comment)");
        $stmt->execute([
            'product_id' => $produtoId,
            'user_id' => $userId,
            'comment' => $comment
        ]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=$produtoId");
        exit;
    }
}

// Conta o número total de comentários
$totalComentariosStmt = $pdo->prepare("SELECT COUNT(*) FROM product_reviews WHERE product_id = :product_id");
$totalComentariosStmt->execute(['product_id' => $produtoId]);
$totalComentarios = $totalComentariosStmt->fetchColumn();

// Calcula o número total de páginas
$totalPaginas = ceil($totalComentarios / $comentariosPorPagina);

// Carrega os comentários da página atual
$comentarios = [];
if ($produtoId) {
    $stmt = $pdo->prepare("SELECT r.comment, r.created_at, u.username 
                           FROM product_reviews r
                           JOIN users u ON r.user_id = u.id
                           WHERE r.product_id = :product_id
                           ORDER BY r.created_at DESC
                           LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':product_id', $produtoId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $comentariosPorPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <?php if ($produto): ?>
            <div id="product-id" style="display:none;"><?= htmlspecialchars($produtoId) ?></div>
            <div class="product-detail">
                <!-- Imagem e informações do produto -->
                <div class="product-detail-image">
                    <img src="<?= htmlspecialchars($produto['image']) ?>" alt="<?= htmlspecialchars($produto['name']) ?>">
                </div>
                <div class="product-detail-info">
                    <h1><?= htmlspecialchars($produto['name']) ?></h1>
                    <p>€<?= number_format($produto['price'], 2, ',', '.') ?></p>
                    <p><?= htmlspecialchars($produto['description']) ?></p>

                    <!-- Controle de stock -->
                    <?php if ($produto['stock'] <= 10 && $produto['stock'] > 0): ?>
                        <p class="low-stock">Apenas <?= $produto['stock'] ?> Unidades restantes em stock!</p>
                    <?php elseif ($produto['stock'] == 0): ?>
                        <p class="low-stock">Stock esgotado!</p>
                    <?php endif; ?>

                    <!-- Opções de tamanho -->
                    <div class="size-options">
                        <span>Tamanho:</span>
                        <?php foreach ($tamanhosDisponiveis as $tamanho): ?>
                            <button type="button" class="size-button" onclick="selectSize(this)">
                                <?= htmlspecialchars($tamanho) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <button id="add-to-cart-button" onclick="addToCart()" <?= $produto['stock'] == 0 ? 'disabled' : '' ?>>
                        Adicionar ao Cesto
                    </button>
                </div>
            </div>

            <!-- Seção de Comentários -->
            <div class="comments-section">
                <h2>Comentários</h2>
                <div id="comments-list">
                    <?php if (!empty($comentarios)): ?>
                        <?php foreach ($comentarios as $comentario): ?>
                            <div class="comment">
                                <p><strong><?= htmlspecialchars($comentario['username']) ?>:</strong></p>
                                <p><?= nl2br(htmlspecialchars($comentario['comment'])) ?></p>
                                <small>Publicado em: <?= htmlspecialchars($comentario['created_at']) ?></small>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Sem comentários. Seja o primeiro a comentar!</p>
                    <?php endif; ?>
                </div>

                <!-- Paginação -->
                <div class="pagination">
                    <?php if ($totalPaginas > 1): ?>
                        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                            <a href="?id=<?= $produtoId ?>&pagina=<?= $i ?>" class="<?= $i == $paginaAtual ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    <?php endif; ?>
                </div>

                <!-- Formulário de Adicionar Comentário -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="post" id="add-comment-form" class="add-comment-form">
                        <textarea name="comment" id="comment" placeholder="Escreva seu comentário..." required></textarea>
                        <button type="submit">Adicionar Comentário</button>
                    </form>
                <?php else: ?>
                    <p>Faça login para adicionar um comentário.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Produto não encontrado.</p>
        <?php endif; ?>
    </main>

    <?php include('../includes/footer.php'); ?>

    <script src="../script/script.js"></script>
</body>

</html>

