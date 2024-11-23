<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Origens Lusas</title>
    <link rel="stylesheet" href="css/header.css"> 
</head>
<body>
<header>
    <div class="container">
        <!-- Adiciona o logo e o texto juntos -->
        <div class="logo-container">
            <img src="images/logo_novo.png" alt="Logo Origens Lusas" class="logo-img">
            <a href="index.php" class="logo-text">ORIGENS LUSAS</a>
        </div>
    </div>
    <div class="container">
        <!-- Barra de pesquisa e hambúrguer no mesmo container -->
        <div class="search-hamburger-container">
            <!-- Barra de pesquisa -->
            <form action="search.php" method="GET" class="search-bar">
                <input type="text" name="query" placeholder="Pesquise produtos">
                <button type="submit">🔍</button>
            </form>

            <!-- Ícone do menu de hambúrguer -->
            <div class="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <!-- Links de navegação -->
        <ul class="nav-links">
            <li><a href="male.php">ROUPA MASCULINA</a></li>
            <li><a href="female.php">ROUPA FEMININA</a></li>
            <?php
                // Verifica a página atual para redirecionar adequadamente as partes superior e inferior
                $current_page = basename($_SERVER['PHP_SELF']);
                $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

                // Mostrar links de parte superior e inferior apenas se não estiver nas páginas específicas correspondentes
                if ($current_page == "male.php" || $current_page == "female.php") {
                    if ($current_page == "male.php") {
                        echo '<li><a href="partesuperior.php?tipo=masculino">PARTE SUPERIOR</a></li>';
                        echo '<li><a href="parteinferior.php?tipo=masculino">PARTE INFERIOR</a></li>';
                        echo '<li><a href="exterior.php?tipo=masculino">EXTERIOR</a></li>';
                    } elseif ($current_page == "female.php") {
                        echo '<li><a href="partesuperior.php?tipo=feminino">PARTE SUPERIOR</a></li>';
                        echo '<li><a href="parteinferior.php?tipo=feminino">PARTE INFERIOR</a></li>';
                        echo '<li><a href="exterior.php?tipo=feminino">EXTERIOR</a></li>';
                    }
                }
            ?>
            <!--<li><a href="accessories.php">ACESSÓRIOS</a></li> -->
        </ul>

        <!-- Links de ações -->
        <ul class="actions">
            <li><a href="login.php">LOGIN</a></li>
            <li><a href="help.php" class="active">AJUDA</a></li>
            <li><a href="cart.php" class="cart-count">CESTO (0)</a></li>
        </ul>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Ativa o menu de hambúrguer
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('open'); // Alterna a classe 'open' no menu
        });

        // Atualiza o contador do carrinho
        updateCartCount();
    });

    function updateCartCount() {
        // Lógica para atualizar o contador do carrinho (se necessário)
        console.log("Atualizar contador do carrinho");
    }
</script>
</body>
</html>
