<?php
session_start();


$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {

    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Exibe mensagem de erro em caso de falha na conexão
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}

// Variáveis para armazenar informações do user logado
$userName = null;
$isAdmin = false; // Indica se o user é admin

// Verifica se o user está logado
if (isset($_SESSION['user_id'])) {
    // Busca informações do user logado na bd
    $stmt = $pdo->prepare('SELECT username, is_admin FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user) {
        $userName = $user['username']; // Armazena o nome do user
        $isAdmin = $user['is_admin'] == 1; // Verifica se o user é admin
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">

    <title>Origens Lusas</title>

</head>

<body>
    <header>
        <div id="header-container">
            <img src="images/Menu.png" alt="" id="menu" onclick="toggleDropdownMenu()">
            <img src="images/logo_novo.png" alt="Origens Lusas" id="logo">

            <!-- Lógica para exibir o nome do user ou o ícone de login -->
            <?php if ($userName): ?>
                <div id="user-name" onclick="toggleUserDropdown()"><?php echo htmlspecialchars($userName); ?></div>
                <div id="user-dropdown">
                    <?php if ($isAdmin): ?>
                        <a href="adm/admin_dashboard.php">Administração</a>
                    <?php else: ?>
                        <a href="user/profile.php">Perfil</a>
                    <?php endif; ?>
                    <a href="login/logout.php">Logout</a>
                </div>
            <?php else: ?>
                <img src="images/User.png" alt="Login" id="user" onclick="window.location.href='login/login.php';">
            <?php endif; ?>
        </div>
        <nav id="dropdownMenu" class="dropdown-menu">
            <div class="menu-section">
                <h2>HOMEM</h2>
                <ul>
                    <li><a href="categorias/male.php">Roupa Masculina</a></li>
                    <li><a href="categorias/male.php?filter=Parte%20Superior">Parte Superior</a></li>
                    <li><a href="categorias/male.php?filter=Parte%20Inferior">Parte Inferior</a></li>
                    <li><a href="categorias/male.php?filter=Exterior">Exterior</a></li>
                </ul>
            </div>
            <div class="menu-section">
                <h2>MULHER</h2>
                <ul>
                    <li><a href="categorias/female.php">Roupa Feminina</a></li>
                    <li><a href="categorias/female.php?filter=Parte%20Superior">Parte Superior</a></li>
                    <li><a href="categorias/female.php?filter=Parte%20Inferior">Parte Inferior</a></li>
                    <li><a href="categorias/female.php?filter=Exterior">Exterior</a></li>
                </ul>
            </div>
        </nav>

    </header>

    <div class="choice">
        <a href="categorias/female.php" class="female">
            <div class="temas">
                <h1>Mulher</h1>
            </div>
        </a>
        <a href="categorias/male.php" class="male">
            <div class="temas">
                <h1>Homem</h1>
            </div>
        </a>
    </div>
    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Origens Lusas. Todos os direitos reservados.</p>
            <ul class="footer-links">
                <li><a href="informacao/terms.php">Termos de Serviço</a></li>
                <li><a href="informacao/privacy.php">Política de Privacidade</a></li>
                <li><a href="informacao/contact.php">Contato</a></li>
            </ul>
        </div>
    </footer>

    <script src="script/script.js"></script>
</body>

</html>