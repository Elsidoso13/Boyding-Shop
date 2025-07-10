<?php
function conecta() {
    // Configuración para Render PostgreSQL usando variables de entorno
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '5432';
    $dbname = getenv('DB_NAME') ?: 'proyecto_cybh';
    $user = getenv('DB_USER') ?: 'postgrest';
    $password = getenv('DB_PASSWORD') ?: 'xIvtQ32tLHJarAIALb2Kh6QEGHzsngFv';

    $connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
    $dbconn = pg_connect($connection_string);

    if (!$dbconn) {
        echo 'Error al conectar a la base de datos: ' . pg_last_error();
        exit();
    }

    return $dbconn;
}
?>