<?php
session_start();

// Verificar se o user é admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$dsn = 'mysql:host=localhost;dbname=web;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Processar ações (adicionar editar ativar desativar)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se o formulário foi enviado
        if (isset($_POST['action'])) { // Verifica a ação solicitada
            if ($_POST['action'] === 'add') {
                // Adicionar novo user
                if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                    $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT); // Cria hash da pass para segurança
                    $stmt = $pdo->prepare('INSERT INTO users (username, email, password, is_admin, active) VALUES (:username, :email, :password, :is_admin, 1)');
                    $stmt->execute([
                        'username' => $_POST['username'], // Nome do user
                        'email' => $_POST['email'], // Email do user
                        'password' => $passwordHash, // Senha do user (hash)
                        'is_admin' => isset($_POST['is_admin']) ? 1 : 0, // Define se o user é admin
                    ]);
                }
            } elseif ($_POST['action'] === 'edit') {
                // Editar user existente
                if (!empty($_POST['user_id']) && !empty($_POST['username']) && !empty($_POST['email'])) {
                    $stmt = $pdo->prepare('UPDATE users SET username = :username, email = :email, is_admin = :is_admin WHERE id = :id');
                    $stmt->execute([
                        'username' => $_POST['username'], // Nome atualizad.
                        'email' => $_POST['email'], // Email atualizado
                        'is_admin' => isset($_POST['is_admin']) ? 1 : 0, // status de admin
                        'id' => $_POST['user_id'], // ID do user a ser atualizado
                    ]);
                }
            } elseif ($_POST['action'] === 'toggle_status') {
                // Ativar ou desativar user
                if (!empty($_POST['user_id'])) {
                    $stmt = $pdo->prepare('UPDATE users SET active = NOT active WHERE id = :id'); // Alterna o estado ativo/inativo
                    $stmt->execute(['id' => $_POST['user_id']]);
                }
            }
        }
        header('Location: manage_users.php');
        exit;
    }

    // Obter todos os utilizadores
    $stmt = $pdo->query('SELECT id, username, email, is_admin, active FROM users'); // Busca todos os users
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Armazena os dados dos users
} catch (PDOException $e) {
    die("Erro ao conectar com a base de dados: " . $e->getMessage()); // Mostra erro em caso de falha
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Utilizadores</title>
    <link rel="stylesheet" href="../css/admin_perfil.css">
</head>

<body class="manage-users">
    <h1>Gerir Utilizadores</h1>

    <!-- Tabela de users -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td> <!-- ID do user -->
                    <td><?php echo htmlspecialchars($user['username']); ?></td> <!-- Nome do user -->
                    <td><?php echo htmlspecialchars($user['email']); ?></td> <!-- Email do user -->
                    <td><?php echo $user['is_admin'] ? 'Sim' : 'Não'; ?></td> <!-- status de admin -->
                    <td>
                        <?php if ($user['active']): ?>
                            <span class="status-active">Ativo</span>
                        <?php else: ?>
                            <span class="status-inactive">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulário para adicionar users -->
    <div class="form-container">
        <h2>Adicionar Novo Utilizador</h2>
        <form action="manage_users.php" method="POST">
            <input type="hidden" name="action" value="add"> <!-- Define a ação como "add" -->
            <label for="username">Nome:</label>
            <input type="text" id="username" name="username" placeholder="Nome do utilizador" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email do utilizador" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password do utilizador" required>
            <label>
                <input type="checkbox" id="is_admin" name="is_admin"> Admin
            </label>
            <button type="submit">Adicionar Utilizador</button>
        </form>
    </div>


    <div class="back-button">
        <button onclick="window.location.href='admin_dashboard.php';">Voltar</button>
    </div>
</body>

</html>