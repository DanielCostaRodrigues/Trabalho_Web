<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Produto - Origens Lusas</title>
    <link rel="stylesheet" href="css/male.css">
</head>
<body>
    <?php include('header/header.php'); ?>

    <?php
        // Definir produtos masculinos, femininos e exteriores
        $produtosMasculinos = [
            1 => ["nome" => "Camisa Branca Casual", "preco" => "40,00", "imagem" => "images/mal/camisabranca.jpg", "descricao" => "Camisa branca casual perfeita para um dia ensolarado."],
            2 => ["nome" => "Camisa Azul Formal", "preco" => "45,00", "imagem" => "images/mal/camisaazul.jpg", "descricao" => "Camisa azul formal ideal para reuniões e eventos."],
            3 => ["nome" => "Hoodie Bege", "preco" => "50,00", "imagem" => "images/mal/sweatbege.jpg", "descricao" => "Hoodie confortável para momentos casuais."],
            4 => ["nome" => "Casaco Clássico Castanho", "preco" => "120,00", "imagem" => "images/mal/casacocastanho.jpg", "descricao" => "Casaco clássico castanho para um estilo sofisticado."],
            5 => ["nome" => "Calção Linho", "preco" => "45,00", "imagem" => "images/mal/calcaolinho.jpg", "descricao" => "Calção leve e confortável para o uso do dia a dia."],
            6 => ["nome" => "Calças Pretas", "preco" => "55,00", "imagem" => "images/mal/calpreta.jpg", "descricao" => "Calças pretas versáteis e confortáveis."],
            7 => ["nome" => "Camisa Listrada", "preco" => "50,00", "imagem" => "images/mal/camisalistrada.jpg", "descricao" => "Camisa listrada para um look estiloso."],
            8 => ["nome" => "Camisa Polo Preta", "preco" => "60,00", "imagem" => "images/mal/campolopreta.jpg", "descricao" => "Camisa polo preta, ideal para qualquer ocasião."],
        ];

        $produtosFemininos = [
            1 => ["nome" => "Vestido Floral", "preco" => "70,00", "imagem" => "images/fem/vestidofloral.jpg", "descricao" => "Um lindo vestido floral perfeito para o verão."],
            2 => ["nome" => "Blusa de Cetim", "preco" => "50,00", "imagem" => "images/fem/blusa.jpg", "descricao" => "Blusa de cetim elegante e confortável."],
            3 => ["nome" => "Saia Midi Bege", "preco" => "60,00", "imagem" => "images/fem/saia.jpg", "descricao" => "Saia midi bege para compor um look casual."],
            4 => ["nome" => "Casaco Feminino Cinza", "preco" => "110,00", "imagem" => "images/fem/casaco.jpg", "descricao" => "Casaco feminino cinza elegante para dias frios."],
            5 => ["nome" => "Calções de Linho", "preco" => "45,00", "imagem" => "images/fem/calcaolinho.jpg", "descricao" => "Calções leves e bonitos para o uso diário"],
            6 => ["nome" => "Calças Flare", "preco" => "55,00", "imagem" => "images/fem/calçasflare.jpg", "descricao" => "Calças flare estilosas para qualquer ocasião."],
            7 => ["nome" => "Camisa Branca", "preco" => "50,00", "imagem" => "images/fem/camisabranca.jpg", "descricao" => "Camisa branca clássica, um essencial para o guarda-roupa."],
            8 => ["nome" => "Casaco Ganga Feminina", "preco" => "80,00", "imagem" => "images/fem/casacoganga.jpg", "descricao" => "Casaco de ganga feminino com um toque moderno."],
        ];

        // Definir produtos exteriores masculinos e femininos
        $produtosExteriorMasculino = [
            3 => ["nome" => "Hoodie Bege", "preco" => "50,00", "imagem" => "images/mal/sweatbege.jpg", "descricao" => "Hoodie confortável para momentos casuais."],
            4 => ["nome" => "Casaco Clássico Castanho", "preco" => "120,00", "imagem" => "images/mal/casacocastanho.jpg", "descricao" => "Casaco clássico castanho para um estilo sofisticado."],
        ];

        $produtosExteriorFeminino = [
            2 => ["nome" => "Casaco Feminino Cinza", "preco" => "110,00", "imagem" => "images/fem/casaco.jpg", "descricao" => "Casaco feminino cinza elegante para dias frios."],
            3 => ["nome" => "Casaco Ganga Feminina", "preco" => "80,00", "imagem" => "images/fem/casacoganga.jpg", "descricao" => "Casaco de ganga feminino com um toque moderno."],
        ];

                // Obtendo o ID do produto e o tipo (masculino, feminino ou exterior) da URL
        $produtoId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

        // Validar se o produto existe no tipo específico
        if ($tipo === 'masculino' && $produtoId && isset($produtosMasculinos[$produtoId])) {
            $produto = $produtosMasculinos[$produtoId];
        } elseif ($tipo === 'feminino' && $produtoId && isset($produtosFemininos[$produtoId])) {
            $produto = $produtosFemininos[$produtoId];
        } elseif ($tipo === 'exterior_masculino' && $produtoId && isset($produtosExteriorMasculino[$produtoId])) {
            $produto = $produtosExteriorMasculino[$produtoId];
        } elseif ($tipo === 'exterior_feminino' && $produtoId && isset($produtosExteriorFeminino[$produtoId])) {
            $produto = $produtosExteriorFeminino[$produtoId];
        } else {
            $produto = null;
        }
            ?>

    <main>
        <?php if ($produto): ?>
            <div class="product-detail">
                <div class="product-detail-image">
                    <img src="<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>">
                </div>
                <div class="product-detail-info">
                    <h1><?php echo $produto['nome']; ?></h1>
                    <p>€<?php echo $produto['preco']; ?></p>
                    <p><?php echo $produto['descricao']; ?></p>
                    <div class="size-options">
                        <span>Tamanho:</span>
                        <button onclick="selectSize(this)">XS</button>
                        <button onclick="selectSize(this)">S</button>
                        <button onclick="selectSize(this)">M</button>
                        <button onclick="selectSize(this)">L</button>
                        <button onclick="selectSize(this)">XL</button>
                        <button onclick="selectSize(this)">XXL</button>
                    </div>
                    <div class="button-container">
                        <button class="add-to-bag" onclick="addToCart()">Adicionar ao Cesto</button>
                        <button class="back-button" onclick="location.reload(); window.history.back()">Voltar</button>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>Produto não encontrado. Por favor, volte e selecione um produto válido.</p>
        <?php endif; ?>
    </main>

    <?php include('footer/footer.php'); ?>
    <script src="script.js"></script>
</body>
</html>
