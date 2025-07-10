<?php
require '../funciones/conecta.php'; // Asegúrate de ajustar esta ruta si es necesario

$con = conecta();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idEmpleado = $_POST['id'];

    // Consulta parametrizada para evitar inyecciones SQL
    $sql = "UPDATE empleados SET eliminado = 1 WHERE id = $1";
    $result = pg_query_params($con, $sql, array($idEmpleado));

    if ($result) {
        echo "1"; // Éxito
    } else {
        echo "0"; // Error
    }

    // Cerrar la conexión
    pg_close($con);
}
?>
