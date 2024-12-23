<?php
// Inicia a sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Inicializa a contagem de produtos no carrinho
    $cartCount = 0;
    if (isset($_SESSION['user_id'])) {
        // Consulta para somar a quantidade total de produtos no carrinho do user logado
        $stmt = $pdo->prepare('SELECT SUM(quantity) AS total_quantity FROM cart WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $cartCount = $result['total_quantity'] ?? 0; // Define 0 caso não haja itens
    }

    // Inicializa o nome do user e verifica se é admin
    $userName = null;
    $isAdmin = false;
    if (isset($_SESSION['user_id'])) {
        // Consulta para obter o nome do user e o status de admin
        $stmt = $pdo->prepare('SELECT username, is_admin FROM users WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $userName = $user['username'];
            $isAdmin = (bool) $user['is_admin']; // Converte para booleano
        }
    }
} catch (PDOException $e) {
    // Exibe mensagem de erro caso ocorra um problema de conexão
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}
?>


<header>
    <div class="container">
        <div class="logo-container">
            <img src="../images/logo_novo.png" alt="Logo Origens Lusas" class="logo-img"> <!-- Logotipo -->
            <a href="../index.php" class="logo-text">ORIGENS LUSAS</a> <!-- Texto do logotipo -->
        </div>
    </div>


    <div class="container">
        <div class="search-hamburger-container">
            <!-- Barra de pesquisa -->
            <form action="../includes/search.php" method="GET" class="search-bar">
                <input type="text" name="query" placeholder="Pesquise produtos" value="<?= htmlspecialchars($_GET['query'] ?? '') ?>">
                <!-- Identifica a página atual para filtrar por categoria -->
                <?php if (basename($_SERVER['PHP_SELF']) === 'male.php'): ?>
                    <input type="hidden" name="category_id" value="1"> <!-- ID da categoria masculina -->
                <?php elseif (basename($_SERVER['PHP_SELF']) === 'female.php'): ?>
                    <input type="hidden" name="category_id" value="2"> <!-- ID da categoria feminina -->
                <?php endif; ?>
                <button type="submit">🔍</button>
            </form>
        </div>


        <ul class="nav-links">
            <li><a href="../categorias/male.php">ROUPA MASCULINA</a></li>
            <li><a href="../categorias/female.php">ROUPA FEMININA</a></li>
        </ul>

        <!-- Ações do user -->
        <ul class="actions">
            <?php if ($userName): ?> <!-- Verifica se o user está logado -->
                <li class="user-menu-container">
                    <div id="user-name" onclick="toggleUserMenu()">
                        <?= htmlspecialchars($userName) ?> <!-- Exibe o nome do user -->
                    </div>
                    <div id="user-menu" class="user-menu">
                        <?php if ($isAdmin): ?> <!-- Link para administração caso seja admin -->
                            <a href="../adm/admin_dashboard.php">Administração</a>
                        <?php else: ?>
                            <a href="../user/profile.php">Perfil</a>
                        <?php endif; ?>
                        <a href="../login/logout.php">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="../login/login.php">Login</a></li>
            <?php endif; ?>
            <li><a href="../informacao/help.php">AJUDA</a></li>
            <li><a href="../carrinho/cart.php" class="cart-count">CESTO (<span id="cart-counter"><?= $cartCount ?></span>)</a></li> <!-- Contador do cesto -->
        </ul>
    </div>
</header>

<script src="../script/script.js"></script>