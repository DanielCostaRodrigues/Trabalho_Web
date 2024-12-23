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

// Variável para armazenar mensagens de feedback
$message = '';

// Processar o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os valores enviados pelo formulário
    $email = trim($_POST['email']); // Remove espaços em branco do início e fim do email
    $password = $_POST['password']; // Pass enviada

    // Consulta para buscar o user com base no email
    $stmt = $pdo->prepare('SELECT id, password, is_admin, active FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]); // Executa a consulta com o email fornecido
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os dados do user como array associativo

    if ($user) { // Se o user existir:
        if ($user['active'] == 0) {
            // Verifica se a conta está inativa
            $message = 'Sua conta está inativa. Entre em contato com o suporte.';
        } elseif (password_verify($password, $user['password'])) {
            // Verifica se a pass corresponde.
            $_SESSION['user_id'] = $user['id']; // Salva o ID do user na sessão
            $_SESSION['is_admin'] = $user['is_admin']; // Salva o status de admin

            // Redireciona para a página de administração ou para a página inicial
            header('Location: ' . ($user['is_admin'] == 1 ? '../adm/admin_dashboard.php' : '../index.php'));
            exit();
        } else {
            // Caso a pass esteja incorreta
            $message = 'Email ou password incorretos!';
        }
    } else {
        // Caso o utilizador não seja encontrado
        $message = 'Email ou password incorretos!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sessão</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>

    <div class="main_login">
        <div class="back_card">
            <div class="image_left"></div> <!-- Área para a imagem decorativa -->
            <div class="form_login">
                <h2 class="title">Iniciar sessão</h2>
                <!-- Formulário de login -->
                <form id="loginForm" action="" method="POST">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Escreva o seu email..." required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Escreva a sua password..." required>

                    <button type="submit">Iniciar sessão</button>
                </form>
                <a href="#" id="forgotPasswordLink">Esqueceu a sua password?</a>
                <a href="../index.php">Voltar ao menu principal</a>
                <a href="register.php">Criar conta</a>
            </div>
        </div>
    </div>

    <!-- Modal para redefinir a password -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Repor a sua password</h2>
            <p>Escreva o seu email para que possa repor a sua password.</p>
            <form id="resetPasswordForm" action="process_reset_password.php" method="POST">
                <label for="resetEmail">Email:</label>
                <input type="email" id="resetEmail" name="resetEmail" placeholder="Escreva o seu email..." required>
                <p id="emailMessage" class="email-message"></p>
                <div class="modal-buttons">
                    <button type="submit" id="requestButton">Repor</button>
                    <button type="button" id="cancelButton">Cancelar</button>
                </div>
            </form>
            <p id="successMessage" class="success-message"></p>
        </div>
    </div>

    <!-- Script para exibir mensagens de erro/sucesso -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const message = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
            if (message) {
                alert(message); // Exibe a mensagem em um alert
                // Limpa os campos do formulário
                const loginForm = document.getElementById('loginForm');
                if (loginForm) {
                    loginForm.reset();
                }
            }
        });
    </script>
    <script src="../script/script.js"></script>
</body>

</html>