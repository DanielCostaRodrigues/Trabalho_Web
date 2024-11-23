<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesto de Compras - Origens Lusas</title>
    <link rel="stylesheet" href="css/male.css"> 
    <link rel="stylesheet" href="css/cart.css"> 
</head>
<body>
    <?php include('header/header.php'); ?>

    <main>
        <div class="cart-section">
            <h1 class="section-title">Cesto de Compras</h1>
            <div id="cart-items" class="cart-items">
                
            </div>
            <div class="cart-summary">
    <h2>Total: <span id="cart-total">€0,00</span></h2>
    <button class="checkout-button">Finalizar Compra</button>
    <button class="back-button" onclick="window.location.href = document.referrer">Voltar</button>
</div>


        </div>
    </main>

    <?php include('footer/footer.php'); ?>

    <script src="script.js"></script> 
    <script>
        // Carrega os produtos do carrinho ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();  
        });
    </script>
</body>
</html>
