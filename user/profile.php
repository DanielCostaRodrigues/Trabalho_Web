<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php');
    exit;
}

$userId = $_SESSION['user_id'];


$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o formulário foi enviado para atualizar a morada
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address'])) {
        $newAddress = trim($_POST['address']); // Obtém a nova morada
        if (!empty($newAddress)) { // Garante que o campo não está vazio
            // Atualiza a morada do user na base de dados
            $stmt = $pdo->prepare("UPDATE users SET address = :address WHERE id = :user_id");
            $stmt->execute(['address' => $newAddress, 'user_id' => $userId]);
        }
    }

    // Consulta para obter os dados atualizados do user
    $stmt = $pdo->prepare("SELECT username, email, address FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do user

    if (!$user) {
        throw new Exception("Utilizador não encontrado."); // Lança exceção se o user não for encontrado
    }

    // Sanitiza os valores obtidos para exibição segura
    $username = htmlspecialchars($user['username'] ?? 'Utilizador não definido');
    $email = htmlspecialchars($user['email'] ?? 'Não disponível');
    $address = htmlspecialchars($user['address'] ?? 'Não fornecida');
} catch (PDOException $e) {
    die("Erro ao conectar com a base de dados: " . $e->getMessage()); // Exibe mensagem de erro em caso de falha na conexão
} catch (Exception $e) {
    die($e->getMessage()); // Exibe mensagens de exceções gerais
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Origens Lusas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body class="all-profile-user">

    <?php include('../includes/header.php'); ?>

    <main>

        <h1>Perfil do Utilizador</h1>

        <!-- Exibição de informações do user -->
        <div class="user-info">
            <h2>Informações Pessoais</h2>
            <p><strong>Nome de Utilizador:</strong> <?php echo $username; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Morada:</strong> <?php echo $address; ?></p>
        </div>

        <!-- Formulário para atualizar a morada -->
        <div class="address-form">
            <h2>Atualizar Morada</h2>
            <form action="profile.php" method="POST">
                <label for="address">Nova Morada:</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                <button type="submit">Atualizar</button>
            </form>
        </div>

        <!-- Histórico de compras -->
        <div class="order-history">
            <h2>Histórico de Compras</h2>
            <div id="orders-container">
                <!-- A última compra será carregada inicialmente via AJAX -->
            </div>
            <div class="buttons">
                <button id="load-more-orders" class="load-more-button" onclick="loadOrders()">Carregar Mais</button>
            </div>
        </div>
    </main>


    <?php include('../includes/footer.php'); ?>


    <script src="../script/script.js"></script>
</body>

</html>