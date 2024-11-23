<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parte Superior - Origens Lusas</title>
    <link rel="stylesheet" href="css/male.css">
</head>
<body>
    <?php include('header/header.php'); ?>

    <main>
        <div class="collection-section">
            <h1 class="section-title">Parte Superior</h1>
            <section class="products">
                <?php
                    
                    $produtosMasculinos = [
                        ["id" => 1, "nome" => "Camisa Branca Casual", "preco" => "40,00", "imagem" => "images/mal/camisabranca.jpg"],
                        ["id" => 2, "nome" => "Camisa Azul Formal", "preco" => "45,00", "imagem" => "images/mal/camisaazul.jpg"],
                        ["id" => 7, "nome" => "Camisa Listrada", "preco" => "50,00", "imagem" => "images/mal/camisalistrada.jpg"],
                        ["id" => 8, "nome" => "Camisa Polo Preta", "preco" => "60,00", "imagem" => "images/mal/campolopreta.jpg"],
                    ];

                    $produtosFemininos = [
                        ["id" => 1,"nome" =>"Vestido Floral", "preco" => "70,00", "imagem" => "images/fem/vestidofloral.jpg"],
                        ["id" => 2, "nome" => "Blusa de Cetim", "preco" => "50,00", "imagem" => "images/fem/blusa.jpg"],
                        ["id" => 7, "nome" => "Camisa Branca", "preco" => "50,00", "imagem" => "images/fem/camisabranca.jpg"],
                        ["id" => 8, "nome" => "Casaco Ganga Feminina", "preco" => "80,00", "imagem" => "images/fem/casacoganga.jpg"],
                    ];

                   
                    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

                    // Filtrar e exibir os produtos
                    if ($tipo === 'masculino') {
                        $produtos = $produtosMasculinos;
                    } elseif ($tipo === 'feminino') {
                        $produtos = $produtosFemininos;
                    } else {
                        $produtos = [];
                    }

                    foreach ($produtos as $produto) {
                        echo "
                        <div class='product'>
                            <img src='{$produto['imagem']}' alt='{$produto['nome']}'>
                            <h2>{$produto['nome']}</h2>
                            <p>€{$produto['preco']}</p>
                            <button onclick=\"location.href='product_detail.php?id={$produto['id']}&tipo={$tipo}'\">Comprar</button>
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
