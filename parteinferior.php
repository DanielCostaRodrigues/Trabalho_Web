<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parte Inferior - Origens Lusas</title>
    <link rel="stylesheet" href="css/male.css">
</head>
<body>
    <?php include('header/header.php'); ?>

    <main>
        <div class="collection-section">
            <h1 class="section-title">Parte Inferior</h1>
            <section class="products">
                <?php
                    // Produtos da parte inferior masculina e feminina
                    $produtosMasculinos = [
                        ["id" => 6, "nome" => "Calças Pretas", "preco" => "55,00", "imagem" => "images/mal/calpreta.jpg"],
                        ["id" => 5, "nome" => "Calção Linho", "preco" => "45,00", "imagem" => "images/mal/calcaolinho.jpg"],
                    ];

                    $produtosFemininos = [
                        ["id" => 3, "nome" => "Saia Midi Bege", "preco" => "60,00", "imagem" => "images/fem/saia.jpg"],
                        ["id" => 5,"nome" => "Calções de Linho", "preco" => "45,00", "imagem" => "images/fem/calcaolinho.jpg"],
                        ["id" => 6, "nome" => "Calças Flare", "preco" => "55,00", "imagem" => "images/fem/calçasflare.jpg"],
                    ];

                 
                    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

                   
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
