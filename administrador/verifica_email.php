<?php
require "../funciones/conecta.php";
$con = conecta();

$email = $_GET['email'];

// Consulta preparada para verificar si el correo ya existe
$query = "SELECT * FROM empleados WHERE correo = $1";
$result = pg_query_params($con, $query, array($email));

if (pg_num_rows($result) > 0) {
    echo 'exist';
} else {
    echo 'not exist';
}
?>
