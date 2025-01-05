<?php
// Conexão com a bd
$dsn = 'mysql:host=localhost;dbname=grupo106;charset=utf8mb4';
$db_user = 'web';
$db_password = 'web';

try {
    // Cria uma nova conexão PDO com a base de dados
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Define o modo de erro como exceção
} catch (PDOException $e) {
    // Caso haja erro na conexão, encerra o script e exibe uma mensagem de erro
    die("Erro ao conectar com a base de dados: " . $e->getMessage());
}
?>
