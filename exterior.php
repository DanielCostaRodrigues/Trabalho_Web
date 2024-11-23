<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exterior - Origens Lusas</title>
    <link rel="stylesheet" href="css/male.css">
</head>
<body>
    <?php include('header/header.php'); ?>

    <main>
        <div class="collection-section">
            <h1 class="section-title">Exterior</h1>
            <section class="products">
                <?php
                    // Definir os produtos de "Exterior" Masculino e Feminino
                    $produtosExteriorMasculino = [
                        ["id" => 3,"nome" => "Hoodie Bege", "preco" => "50,00", "imagem" => "images/mal/sweatbege.jpg"],
                        ["id" => 4, "nome" => "Casaco Clássico Castanho", "preco" => "120,00", "imagem" => "images/mal/casacocastanho.jpg"],
                    ];

                    $produtosExteriorFeminino = [
                        ["id" => 2, "nome" => "Casaco Feminino Cinza", "preco" => "110,00", "imagem" => "images/fem/casaco.jpg"],
                        ["id" => 3, "nome" => "Casaco Ganga Feminina", "preco" => "80,00", "imagem" => "images/fem/casacoganga.jpg"],
                    ];

                   
                    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

                    // Filtrar e exibir os produtos
                    if ($tipo === 'masculino') {
                        $produtos = $produtosExteriorMasculino;
                    } elseif ($tipo === 'feminino') {
                        $produtos = $produtosExteriorFeminino;
                    } else {
                        $produtos = [];
                    }

                    foreach ($produtos as $produto) {
                        echo "
                        <div class='product'>
                            <img src='{$produto['imagem']}' alt='{$produto['nome']}'>
                            <h2>{$produto['nome']}</h2>
                            <p>€{$produto['preco']}</p>
                            <button onclick=\"location.href='product_detail.php?id={$produto['id']}&tipo=exterior_{$tipo}'\">Comprar</button>

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
