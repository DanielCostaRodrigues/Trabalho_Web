<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sessão</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="main_login">
        <div class="back_card">
            <div class="image_left"></div>
            <div class="form_login">
                <h2 class="title">Iniciar sessão</h2>
                <form action="#" method="POST">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Escreva o seu email..." required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Escreva a sua password..." required>

                    <button type="submit">Iniciar sessão</button>
                </form>
                <a href="#" id="forgotPasswordLink">Esqueceu a sua password?</a>
                <a href="index.php">Voltar ao menu principal</a>
                <a href="register.php">Criar conta</a>
            </div>
        </div>
    </div>

    
   <!-- Modal para redefinir a pass -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Repor a sua password</h2>
        <p>Escreva o seu email para que possa repor a sua password.</p>
        <form id="resetPasswordForm" action="#" method="POST">
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


    <script src="script.js"></script>
</body>
</html>
