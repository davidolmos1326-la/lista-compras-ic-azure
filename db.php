
<?php
// Leer las variables de entorno configuradas en Azure
$host = getenv('DB_HOST') ?: 'compras-db-servidordavid.mysql.database.azure.com';
$dbname = getenv('DB_NAME') ?: 'lista_compras';
$username = getenv('DB_USER') ?: 'userdb1';
$password = getenv('DB_PASS') ?: 'D4v1dholmos3-';

// Ruta al certificado de confianza del sistema en Azure App Service Linux
$sslCa = '/etc/ssl/certs/ca-certificates.crt';

try {
    // Opciones de conexión PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    // Configurar SSL si el controlador PDO MySQL lo admite
    if (defined('PDO::MYSQL_ATTR_SSL_CA') && file_exists($sslCa)) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
    } else {
        throw new RuntimeException(
            'No se encontró el certificado SSL o el controlador PDO MySQL no lo admite.'
        );
    }

    // Conexión segura con Azure MySQL
    $dsn = "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4";

    $pdo = new PDO($dsn, $username, $password, $options);

} catch (Throwable $e) {
    // Registrar el detalle técnico en los registros del servidor,
    // sin mostrar contraseñas ni datos de conexión al visitante.
    error_log('Error de conexión MySQL: ' . $e->getMessage());

    http_response_code(500);
    die('No se pudo conectar a la base de datos. Revisa la configuración SSL y las variables de entorno de Azure.');
}
?>
