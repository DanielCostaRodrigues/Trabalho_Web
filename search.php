<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Pesquisa</title>
    <link rel="stylesheet" href="css/search.css"> 
</head>
<body>
    <header>
    <?php include('header/header.php'); ?>
    </header>
    
    <main>
        <h1>Resultado da Pesquisa</h1>
        <?php
     
        if (!empty($_GET['query'])) {
            $query = htmlspecialchars($_GET['query']); 
            echo "<p>Você pesquisou por: <strong>" . $query . "</strong></p>";
           
            echo "<p>Resultados da pesquisa ainda não foram implementados.</p>";
        } else {
            echo "<p>Nenhuma pesquisa realizada. Por favor, insira um termo de pesquisa.</p>";
        }
        ?>
    </main>

    
     <?php include('footer/footer.php'); ?>
    
    <script src="script.js"></script> 
</body>
</html>
