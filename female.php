<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coleção Feminina - Origens Lusas</title>
    <link rel="stylesheet" href="css/male.css"> 
</head>
<body>
    
    <?php include('header/header.php'); ?>

    <main>
        <div class="collection-section">
            <h1 class="section-title">Coleção Feminina</h1>
            <section class="products">
               
                <?php
                    
                    $produtos = [
                        ["id" => 1, "nome" => "Vestido Floral", "preco" => "70,00", "imagem" => "images/fem/vestidofloral.jpg"],
                        ["id" => 2, "nome" => "Blusa de Cetim", "preco" => "50,00", "imagem" => "images/fem/blusa.jpg"],
                        ["id" => 3, "nome" => "Saia Midi Bege", "preco" => "60,00", "imagem" => "images/fem/saia.jpg"],
                        ["id" => 4, "nome" => "Casaco Feminino Cinza", "preco" => "110,00", "imagem" => "images/fem/casaco.jpg"],
                        ["id" => 5, "nome" => "Calções de Linho", "preco" => "45,00", "imagem" => "images/fem/calcaolinho.jpg"],
                        ["id" => 6, "nome" => "Calças Flare", "preco" => "55,00", "imagem" => "images/fem/calçasflare.jpg"],
                        ["id" => 7, "nome" => "Camisa Branca", "preco" => "50,00", "imagem" => "images/fem/camisabranca.jpg"],
                        ["id" => 8, "nome" => "Casaco Ganga Feminina", "preco" => "80,00", "imagem" => "images/fem/casacoganga.jpg"],
                    ];

                    foreach ($produtos as $produto) {
                        echo "
                        <div class='product'>
                            <img src='{$produto['imagem']}' alt='{$produto['nome']}'>
                            <h2>{$produto['nome']}</h2>
                            <p>€{$produto['preco']}</p>
                            <button onclick=\"location.href='product_detail.php?id={$produto['id']}&tipo=feminino'\">Comprar</button>
                        </div>
                        ";
                    }
                    ?>
            </section>
        </div>
    </main>
    
   
    <?php include('footer/footer.php'); ?>

    
</body>
</html>
