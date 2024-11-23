<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coleção Masculina - Origens Lusas</title>
    <link rel="stylesheet" href="css/male.css"> 
</head>
<body>
   
    <?php include('header/header.php'); ?>

    <main>
        <div class="collection-section">
            <h1 class="section-title">Coleção Masculina</h1>
            <section class="products">
                
                <?php
                    // Definindo os produtos masculinos
                    $produtos = [
                        1 => ["nome" => "Camisa Branca Casual", "preco" => "40,00", "imagem" => "images/mal/camisabranca.jpg"],
                        2 => ["nome" => "Camisa Azul Formal", "preco" => "45,00", "imagem" => "images/mal/camisaazul.jpg"],
                        3 => ["nome" => "Hoodie Bege", "preco" => "50,00", "imagem" => "images/mal/sweatbege.jpg"],
                        4 => ["nome" => "Casaco Clássico Castanho", "preco" => "120,00", "imagem" => "images/mal/casacocastanho.jpg"],
                        5 => ["nome" => "Calção Linho", "preco" => "45,00", "imagem" => "images/mal/calcaolinho.jpg"],
                        6 => ["nome" => "Calças Pretas", "preco" => "55,00", "imagem" => "images/mal/calpreta.jpg"],
                        7 => ["nome" => "Camisa Listrada", "preco" => "50,00", "imagem" => "images/mal/camisalistrada.jpg"],
                        8 => ["nome" => "Camisa Polo Preta", "preco" => "60,00", "imagem" => "images/mal/campolopreta.jpg"],
                    ];

                    
                    foreach ($produtos as $id => $produto) {
                        echo "
                        <div class='product'>
                            <img src='{$produto['imagem']}' alt='{$produto['nome']}'>
                            <h2>{$produto['nome']}</h2>
                            <p>€{$produto['preco']}</p>
                            <button onclick=\"location.href='product_detail.php?id={$id}&tipo=masculino'\">Comprar</button>
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
