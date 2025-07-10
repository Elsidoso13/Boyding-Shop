<?php
require_once '../funciones/conecta.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $con = conecta();
    
    // Escapar el correo para evitar inyección SQL
    $correo = pg_escape_string($con, $_POST['correo']);

    // Consulta a la base de datos
    $sql = "SELECT * FROM empleados WHERE correo = '$correo'";
    $result = pg_query($con, $sql);

    // Verificar si hay resultados
    if (pg_num_rows($result) > 0) {
        echo json_encode(['existe' => true, 'mensaje' => "El correo $correo ya existe."]);
    } else {
        echo json_encode(['existe' => false]);
    }

    // Cerrar conexión
    pg_close($con);
}
?>
