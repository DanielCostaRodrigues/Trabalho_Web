<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main_register">
        <div class="back_card">
            <div class="image_left"></div>
            <div class="form_register">
                <h2 class="title">Criar Conta</h2>
                <form action="#" method="POST">
                    <label for="username">Nome de Utilizador:</label>
                    <input type="text" id="username" name="username" placeholder="Escreva o seu nome..." required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Escreva o seu email..." required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Crie uma senha..." required>

                    <button type="submit">Registar</button>
                </form>
                
                <div class="buttons">
                    <a href="index.php" class="button-secondary">Voltar ao Menu</a>
                    <a href="login.php" class="button-primary">Já tem conta?</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
