<?php
$host = 'localhost';
$port = '5432';
$dbname = 'adm_futevolei';
$user = 'postgres';
$password = '264855'; 

try {
    $conn = new PDO("pgsql:host=$host; port=$port; dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>