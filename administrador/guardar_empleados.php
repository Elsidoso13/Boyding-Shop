<?php
require_once '../funciones/conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $con = conecta();

    // Escapar y preparar los datos de entrada
    $nombre = pg_escape_string($con, $_POST['nombre']);
    $apellidos = pg_escape_string($con, $_POST['apellidos']);
    $correo = pg_escape_string($con, $_POST['correo']);
    $password = md5(pg_escape_string($con, $_POST['password']));
    $rol = intval($_POST['rol']);

    $foto = $_FILES['foto'];
    if ($foto['error'] == 0) {
        $nombreFotoOriginal = $foto['name'];
        $extension = pathinfo($nombreFotoOriginal, PATHINFO_EXTENSION);

        // Encriptar el nombre de la foto
        $nombreFotoEncriptado = md5(uniqid(rand(), true)) . '.' . $extension;
        $ruta = "../imagenes/" . $nombreFotoEncriptado;

        if (move_uploaded_file($foto['tmp_name'], $ruta)) {
            // Crear consulta SQL
            $sql = "INSERT INTO empleados (nombre, apellidos, correo, pass, rol, archivo_nombre, archivo_file) 
                    VALUES ($1, $2, $3, $4, $5, $6, NULL)";

            // Preparar y ejecutar consulta con parámetros
            $result = pg_query_params($con, $sql, [
                $nombre,
                $apellidos,
                $correo,
                $password,
                $rol,
                $nombreFotoEncriptado
            ]);

            if ($result) {
                header("Location: empleados_lista.php");
                exit;
            } else {
                echo "Error al registrar el empleado: " . pg_last_error($con);
            }
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        echo "Se requiere una foto para el empleado.";
    }

    // Cerrar conexión
    pg_close($con);
}
?>
