<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Retorna o código HTTP 403 para acesso negado
    exit('Acesso negado.'); // Encerra o script com mensagem de erro
}

$userId = $_SESSION['user_id']; // ID do user logado
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página atual para paginação
$perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 3; // Itens por página (3)
$offset = ($page - 1) * $perPage; // Calcula o offset para a consulta SQL


require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

try {


    // Consulta para obter o total de pedidos únicos do user
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT order_id) FROM order_history WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $totalOrders = $stmt->fetchColumn(); // Obtém o total de pedidos únicos

    if ($totalOrders == 0 && $page == 1) {
        // Se nenhum pedido for encontrado e a página for a primeira
        echo '<p>Você ainda não realizou nenhuma compra.</p>';
        exit;
    }

    // Consulta para obter os pedidos com paginação
    $stmt = $pdo->prepare("
        SELECT DISTINCT order_id, order_date, delivery_address, payment_method
        FROM order_history
        WHERE user_id = :user_id
        ORDER BY order_date DESC
        LIMIT :per_page OFFSET :offset
    ");
    // Liga os parâmetros para limitar e organizar os resultados
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':per_page', $perPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtém os pedidos encontrados

    if (empty($orders)) {
        // Se não houver mais pedidos para carregar
        exit;
    }

    // Calcula o número do pedido mais recente
    $orderNumber = $totalOrders - $offset;

    // Itera pelos pedidos e exibe os detalhes
    foreach ($orders as $order) {
        $orderId = $order['order_id']; // ID único do pedido

        // Exibe os detalhes gerais do pedido
        echo "<h3>Pedido #{$orderNumber}</h3>";
        echo "<p><strong>Data do Pedido:</strong> " . date('d/m/Y H:i', strtotime($order['order_date'])) . "</p>";
        echo "<p><strong>Endereço de Entrega:</strong> " . htmlspecialchars($order['delivery_address']) . "</p>";
        echo "<p><strong>Método de Pagamento:</strong> " . htmlspecialchars($order['payment_method']) . "</p>";
        echo "<table><thead><tr><th>Produto</th><th>Quantidade</th><th>Preço Total</th></tr></thead><tbody>";

        // Consulta para obter os produtos do pedido
        $stmtItems = $pdo->prepare("
            SELECT p.name AS product_name, o.quantity, o.total_price
            FROM order_history o
            JOIN products p ON o.product_id = p.id
            WHERE o.user_id = :user_id AND o.order_id = :order_id
        ");
        $stmtItems->execute(['user_id' => $userId, 'order_id' => $orderId]);
        $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC); // Obtém os produtos do pedido

        // Itera pelos produtos do pedido e exibe os detalhes
        foreach ($items as $item) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($item['product_name']) . "</td>"; // Nome do produto
            echo "<td>" . htmlspecialchars($item['quantity']) . "</td>"; // Quantidade do produto
            echo "<td>€" . number_format($item['total_price'], 2, ',', '.') . "</td>"; // Preço total do produto
            echo "</tr>";
        }

        echo '</tbody></table><hr>'; // Fecha a tabela e adiciona uma linha divisória
        $orderNumber--; // Decrementa o número local do pedido
    }
} catch (PDOException $e) {
    // Em caso de erro na conexão ou consulta ao banco de dados
    http_response_code(500); // Retorna código HTTP 500 (erro interno do servidor)
    exit('Erro ao carregar pedidos: ' . $e->getMessage()); // Exibe a mensagem de erro
}
