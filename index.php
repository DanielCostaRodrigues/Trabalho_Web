<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Origens Lusas</title>
</head>
<body>
<header>
    <div id="header-container">
        <img src="images/Menu.png" alt="" id="menu" onclick="toggleDropdownMenu()"> 
        <img src="images/logo_novo.png" alt="Origens Lusas" id="logo">
        <img src="images/User.png" href="../login.php" alt="Login" id="user" onclick="window.location.href='login.php';">
    </div>

   
    <nav id="dropdownMenu" class="dropdown-menu">
    <div class="menu-section">
        <h2>HOMEM</h2>
        <ul>
            <li><a href="male.php">Roupa Masculina</a></li>
            <li><a href="partesuperior.php?tipo=masculino">Parte Superior</a></li> 
            <li><a href="parteinferior.php?tipo=masculino">Parte Inferior</a></li> 
            <li><a href="exterior.php?tipo=masculino">Exterior</a></li> 
        </ul>
    </div>
    <div class="menu-section">
        <h2>MULHER</h2>
        <ul>
            <li><a href="female.php">Roupa Feminina</a></li>
            <li><a href="partesuperior.php?tipo=feminino">Parte Superior</a></li> 
            <li><a href="parteinferior.php?tipo=feminino">Parte Inferior</a></li> 
            <li><a href="exterior.php?tipo=feminino">Exterior</a></li> 
        </ul>
    </div>
</nav>

</header>

<div class="choice">
  
    <a href="female.php" class="female">
        <div class="temas">
            <h1>Mulher</h1>
        </div>
    </a>

   
    <a href="male.php" class="male">
        <div class="temas">
            <h1>Homem</h1>
        </div>
    </a>
</div>

<?php
    include('footer/footer.php'); 
?>
<script src="script.js"></script>
</body>
</html>
