<?php
// Intentar leer las variables de entorno de Azure, si no, usar localhost
$host = getenv('DB_HOST') ?: 'compras-db-servidordavid.mysql.database.azure.com';
$dbname = getenv('DB_NAME') ?: 'lista_compras';
$username = getenv('DB_USER') ?: 'userdb1';
$password = getenv('DB_PASS') ?: 'D4v1dholmos3-';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
