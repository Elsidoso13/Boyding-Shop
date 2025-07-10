<?php
require "../funciones/conecta.php"; // Conexión a PostgreSQL
$con = conecta(); // Utiliza la función de conexión a PostgreSQL

$nombre = $_REQUEST['nombre'];

$archivo_n = '';
$archivo = '';

// Verificar si se ha subido un archivo
if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $archivo_n = $_FILES['archivo']['name'];
    $archivo_tmp = $_FILES['archivo']['tmp_name'];

    // Obtener la extensión del archivo
    $extension = strtolower(pathinfo($archivo_n, PATHINFO_EXTENSION));

    // Extensiones permitidas
    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    // Validar el tipo de archivo
    if (in_array($extension, $extensiones_permitidas)) {
        // Generar un nombre único para el archivo
        $archivo = md5($archivo_n . time()) . '.' . $extension;

        // Mover el archivo subido al directorio deseado
        move_uploaded_file($archivo_tmp, "plantillas/" . $archivo);
    } else {
        echo 'Tipo de archivo no permitido.';
        exit;
    }
}

// Consulta parametrizada para evitar inyecciones SQL
$sql = "INSERT INTO Promociones (NOMBRE_PROMO, ARCHIVO) VALUES ($1, $2)";
$res = pg_query_params($con, $sql, [$nombre, $archivo]);

// Verificar si la consulta fue exitosa
if (!$res) {
    echo "Error al insertar la promoción: " . pg_last_error($con);
    exit;
}

// Redirigir a la lista de promociones
header("Location: Promociones_Lista.php");

// Cerrar conexión
pg_close($con);
?>
