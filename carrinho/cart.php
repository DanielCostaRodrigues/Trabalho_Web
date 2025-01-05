<?php
session_start();

if (!isset($_SESSION['user_id'])) {

    header('Location: ../login/login.php');
    exit;
}


$userId = $_SESSION['user_id'];
require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

try {


    // Consulta para buscar os produtos do carrinho do user logado.
    $stmt = $pdo->prepare("
        SELECT c.id AS cart_id, p.name, p.price, p.image, c.quantity, c.size
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $userId]); // Substitui o placeholder :user_id pelo ID do user.
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC); // Busca todos os produtos do carrinho

    // Calcula o total do carrinho somando os preços multiplicados pelas quantidades
    $total = array_reduce($cartItems, function ($carry, $item) {
        return $carry + ($item['price'] * $item['quantity']);
    }, 0); // O valor inicial ($carry) é 0
} catch (PDOException $e) {
    // Mostra uma mensagem de erro se a conexão com o banco falhar
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesto de Compras</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="cart">
    <?php include('../includes/header.php'); ?>

    <main>
        <div class="cart-section">
            <h1 class="section-title">Cesto de Compras</h1>
            <div id="cart-items" class="cart-items">
                <?php if (!empty($cartItems)): ?> <!-- Verifica se há produtos no carrinho -->
                    <?php foreach ($cartItems as $item): ?> <!-- Percorre o array $cartItems, onde cada elemento é um item do carrinho -->
                        <div class="cart-item" data-cart-id="<?= $item['cart_id'] ?>"> <!-- Adiciona o ID do carrinho como atributo -->
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image"> <!-- Mostra a imagem do produto -->
                            <div class="cart-item-details">
                                <h2><?= htmlspecialchars($item['name']) ?></h2> <!-- Mostra o nome do produto. -->
                                <p>Preço: <?= number_format($item['price'], 2, ',', '.') ?> €</p> <!-- Mostra o preço formatado -->
                                <p>Tamanho: <?= htmlspecialchars($item['size']) ?></p> <!-- Mostra o tamanho do produto -->
                                <p>Quantidade: <span class="quantity"><?= htmlspecialchars($item['quantity']) ?></span></p> <!-- Mostra a quantidade -->
                                <div>
                                    <label for="quantity-to-remove-<?= $item['cart_id'] ?>">Remover:</label>
                                    <input type="number" id="quantity-to-remove-<?= $item['cart_id'] ?>" min="1" max="<?= htmlspecialchars($item['quantity']) ?>" value="1"> <!-- Campo para quantidade a remover -->
                                    <button class="remove-button" onclick="removeFromCart(<?= $item['cart_id'] ?>)">Remover</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>

                    <p>O cesto está vazio.</p>
                <?php endif; ?>
            </div>

            <!-- Resumo do carrinho -->
            <div class="cart-summary">
                <h2>Total: <span id="cart-total"><?= number_format($total, 2, ',', '.') ?> €</span></h2> <!-- Total dp carrinho -->

                <?php if (!empty($cartItems)): ?> <!-- Botão finalizar compra caso hja produtos -->
                    <button class="checkout-button" onclick="window.location.href = '../carrinho/escolha_compra.php';">Finalizar Compra</button>
                <?php endif; ?>
                <button class="back-button" onclick="window.history.back()">Voltar</button>
            </div>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>

    <script src="../script/script.js" defer></script>
</body>

</html>