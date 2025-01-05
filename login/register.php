<?php
// Início do script PHP para processar o registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verifica se o formulário foi enviado via POST
    $username = $_POST['username']; // Obtém o nome do user
    $email = $_POST['email']; // Obtém o email
    $password = $_POST['password']; // Obtém a pass

    require_once '../includes/db_connection.php'; // Inclui o ficheiro de conexão com a base de dados

    try {

        // Verifica se o email já está registado
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]); // Executa a consulta com o email fornecido
        if ($stmt->fetch()) { // Se encontrar um resultado
            echo "<script>alert('Este email já está registrado!'); window.location.href = 'register.php';</script>";
            exit();
        }

        // Hash da pass para segurança
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Criptografa a pass

        // Insere o novo user na base de dados
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, NOW())');
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword // Armazena a pass criptografada
        ]);

        // Redireciona para a página de login com uma mensagem de sucesso
        echo "<script>alert('Conta criada com sucesso! Faça login.'); window.location.href = '../login/login.php';</script>";
    } catch (PDOException $e) {
        // Exibe uma mensagem de erro se a conexão falhar
        die("Erro ao conectar com a base de dados: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="main_register">
        <div class="back_card">
            <div class="image_left"></div>
            <div class="form_register">
                <h2 class="title">Criar Conta</h2>
                <!-- Formulário de registo -->
                <form action="register.php" method="POST">
                    <label for="username">Nome de Utilizador:</label>
                    <input type="text" id="username" name="username" placeholder="Escreva o seu nome..." required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Escreva o seu email..." required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Crie a sua password..." required>

                    <button type="submit">Registar</button>
                </form>

                <!-- Botões adicionais -->
                <div class="buttons">
                    <a href="../index.php" class="button-secondary">Voltar ao Menu</a>
                    <a href="../login/login.php" class="button-primary">Já tem conta?</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>