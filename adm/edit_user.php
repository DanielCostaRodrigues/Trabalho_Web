<?php
session_start();

// Verificar se o utilizador é admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados



try {




    if (!isset($_GET['id'])) {
        header('Location: manage_users.php');
        exit;
    }

    $userId = $_GET['id']; // Guarda o ID do user fornecido via GET

    // Obter informações do user
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id'); // Query para buscar as informações do user
    $stmt->execute(['id' => $userId]); // Executa a query com o ID guardado
    $user = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém as informações do user

    if (!$user) { // Verifica se o user não foi encontrado
        echo "Utilizador não encontrado."; // Exibe mensagem de erro
        exit;
    }

    // Processar atualização do utilizador
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se o formulário foi submetido
        $username = $_POST['username']; // Captura o novo nome de user
        $email = $_POST['email']; // Captura o novo e-mail do user
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0; // Define o valor de admin baseado no checkbox
        $active = isset($_POST['active']) ? 1 : 0; // Define o status de ativo baseado no checkbox

        // Atualizar user
        $stmt = $pdo->prepare('UPDATE users SET username = :username, email = :email, is_admin = :is_admin, active = :active WHERE id = :id'); // Query de atualização.
        $stmt->execute([
            'username' => $username, // Nome atualizado
            'email' => $email, // E-mail atualizado
            'is_admin' => $isAdmin, // Status de admin
            'active' => $active, // Status de ativo
            'id' => $userId // ID do user a ser atualizado
        ]);

        header('Location: manage_users.php');
        exit;
    }
} catch (PDOException $e) {
    // Mostra uma mensagem de erro caso ocorra uma falha na bd.
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Utilizador</title>
    <link rel="stylesheet" href="../css/admin_perfil.css">
</head>

<body class="edit-user">
    <div class="form-container">
        <h2>Editar Utilizador</h2>
        <form action="" method="POST"> <!-- Formulário para edição dos dados do user -->
            <label for="username">Nome:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required> <!-- nome -->

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required> <!-- e-mail -->


            <label>
                <input type="checkbox" name="is_admin" <?php echo $user['is_admin'] ? 'checked' : ''; ?>> Admin <!-- Checkbox para definir se o user é admin -->
            </label>
            <label>
                <input type="checkbox" name="active" <?php echo $user['active'] ? 'checked' : ''; ?>> Ativo <!-- Checkbox para definir se o user está ativo. -->
            </label>

            <button type="submit">Guardar Alterações</button>
    </div>

    <div class="back-button">
        <button onclick="window.location.href='manage_users.php';">Voltar</button>
    </div>
</body>

</html>