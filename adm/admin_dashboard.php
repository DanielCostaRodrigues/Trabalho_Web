<?php
include('../adm/admin_check.php'); // Verifica se é admin    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../css/admin_perfil.css">
</head>

<body class="admin-dashboard">
    <header class="admin-header-dashboard">
        Painel de Administração
    </header>

    <div class="admin-container-dashboard"> <!-- dashboard do adm -->
        <h1>Bem-vindo ao Painel de Administração</h1>
        <p>Selecione uma das opções abaixo para começar a gerir o sistema:</p>
        <div class="admin-links-dashboard">
            <!-- ir para pag gerir produtos -->
            <form action="../adm/manage_products.php" method="get">
                <button type="submit">Gerir Produtos</button>
            </form>
            <!-- ir para pag gerir catg -->
            <form action="../adm/manage_categories.php" method="get">
                <button type="submit">Gerir Categorias</button>
            </form>
            <!-- ir para gerir users. -->
            <form action="../adm/manage_users.php" method="get">
                <button type="submit">Gerir Utilizadores</button>
            </form>
        </div>
    </div>

    <footer class="footer-dashboard">
        &copy; <?php echo date('Y'); ?> Origens Lusas. Todos os direitos reservados. <!-- Mete o ano atual -->
        <br>
        <a href="../index.php">Voltar ao Site</a>
    </footer>
</body>

</html>