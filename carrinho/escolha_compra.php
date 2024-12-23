<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obter o endereço padrão do user
    $stmt = $pdo->prepare("SELECT address FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $defaultAddress = $user['address'] ?? ''; // Define o endereço padrão se existir

    $success = $error = ''; // Inicializa mensagens de erro e sucesso

    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se o formulário foi enviado
        // Captura os dados do formulário
        $selectedAddress = $_POST['address_option'] === 'other' ? trim($_POST['new_address']) : $defaultAddress;
        $paymentMethod = $_POST['payment_method'];

        // Validações básicas
        if (empty($selectedAddress)) {
            $error = "O endereço não pode estar vazio.";
        } elseif (empty($paymentMethod)) {
            $error = "Por favor, selecione um método de pagamento.";
        } else {
            // Busca os itens do carrinho
            $stmt = $pdo->prepare('
                SELECT c.product_id, c.quantity, p.price, p.stock 
                FROM cart c
                JOIN products p ON c.product_id = p.id
                WHERE c.user_id = :user_id
            ');
            $stmt->execute(['user_id' => $userId]);
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($cartItems)) {
                $orderDate = date('Y-m-d H:i:s'); // Data e hora atuais para o pedido

                // Gerar um `order_id` único
                $stmt = $pdo->prepare("SELECT MAX(order_id) AS max_order_id FROM order_history");
                $stmt->execute();
                $lastOrderId = $stmt->fetch(PDO::FETCH_ASSOC)['max_order_id'] ?? 0;
                $newOrderId = $lastOrderId + 1;

                foreach ($cartItems as $item) {
                    // Verifica se há stock suficiente
                    if ($item['stock'] < $item['quantity']) {
                        $error = "Erro: stock insuficiente para o produto ID " . $item['product_id'];
                        break;
                    }

                    $totalPrice = $item['price'] * $item['quantity']; // Calcula o preço total do produto

                    // Insere os produtos no histórico de pedidos
                    $stmt = $pdo->prepare("
                        INSERT INTO order_history (order_id, user_id, product_id, quantity, total_price, order_date, delivery_address, payment_method)
                        VALUES (:order_id, :user_id, :product_id, :quantity, :total_price, :order_date, :delivery_address, :payment_method)
                    ");
                    $stmt->execute([
                        'order_id' => $newOrderId,
                        'user_id' => $userId,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'total_price' => $totalPrice,
                        'order_date' => $orderDate,
                        'delivery_address' => $selectedAddress,
                        'payment_method' => $paymentMethod,
                    ]);

                    // Atualiza o stock do produto
                    $stmt = $pdo->prepare("UPDATE products SET stock = stock - :quantity WHERE id = :product_id AND stock >= :quantity");
                    $stmt->execute([
                        'quantity' => $item['quantity'],
                        'product_id' => $item['product_id']
                    ]);

                    // Verifica se a atualização do stock falhou
                    if ($stmt->rowCount() == 0) {
                        $error = "Erro: stock insuficiente para o produto ID " . $item['product_id'];
                        break;
                    }
                }

                // Se não houve erros finaliza a compra
                if (empty($error)) {
                    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id"); // Esvazia o carrinho
                    $stmt->execute(['user_id' => $userId]);

                    unset($_SESSION['cart']); // Remove o carrinho da sessão
                    $success = "Compra finalizada com sucesso!";
                }
            } else {
                $error = "Seu carrinho está vazio.";
            }
        }
    }
} catch (PDOException $e) {
    die("Erro ao conectar com a base de dados: " . $e->getMessage()); // Exibe mensagem de erro em caso de falha na conexão
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escolha de Compra - Origens Lusas</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="checkout">
    <?php include('../includes/header.php'); ?>

    <main>
        <div class="purchase-options-container">
            <h1>Finalizar Compra</h1>

            <!-- Mensagens de erro ou sucesso -->
            <?php if (!empty($error)): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php elseif (!empty($success)): ?>
                <p class="success-message"><?php echo $success; ?></p>
            <?php endif; ?>

            <!-- Formulário de compra -->
            <?php if (empty($success)): ?>
                <form method="POST" action="">
                    <div class="form-section">
                        <h2>Endereço de Entrega</h2>
                        <label>
                            <input type="radio" name="address_option" value="default" checked onclick="toggleAddressInput(false)">
                            Usar endereço da conta: <?php echo htmlspecialchars($defaultAddress ?: 'Não fornecida'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="address_option" value="other" onclick="toggleAddressInput(true)">
                            Usar outro endereço:
                        </label>
                        <input type="text" name="new_address" id="new_address" style="display: none; margin-top: 10px;" placeholder="Digite o novo endereço">
                    </div>

                    <div class="form-section">
                        <h2>Método de Pagamento</h2>
                        <label>
                            <input type="radio" name="payment_method" value="Cartão de Crédito" required> Cartão de Crédito
                        </label><br>
                        <label>
                            <input type="radio" name="payment_method" value="PayPal" required> PayPal
                        </label><br>
                        <label>
                            <input type="radio" name="payment_method" value="Referência Multibanco" required> Referência Multibanco
                        </label>
                    </div>

                    <div class="form-section">
                        <button type="submit" class="finalize-button">Confirmar e Finalizar</button>
                        <button type="button" onclick="history.back()" class="back-button">Voltar</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>
    <script src="../script/script.js"></script>
</body>

</html>