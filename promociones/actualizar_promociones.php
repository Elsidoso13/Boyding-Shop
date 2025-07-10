<?php
require "../funciones/conecta.php";
$con = conecta();

$id = $_POST['id'];
$nombre = $_POST['nombre'];

if (!empty($_FILES['foto']['name'])) {
    $archivo_n = $_FILES['foto']['name'];
    $archivo_tmp = $_FILES['foto']['tmp_name'];

    // Obtener la extensión del archivo
    $extension = strtolower(pathinfo($archivo_n, PATHINFO_EXTENSION));

    // Extensiones permitidas
    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($extension, $extensiones_permitidas)) {
        // Generar un nombre único para el archivo
        $archivo = md5($archivo_n . time()) . '.' . $extension;

        // Mover el archivo subido a la carpeta designada
        if (move_uploaded_file($archivo_tmp, "plantillas/" . $archivo)) {
            $sql = "UPDATE promociones SET nombre_promo='$nombre', archivo='$archivo' WHERE ID_PROMO='$id'";
        } else {
            echo "Error al subir el archivo.";
            exit();
        }
    } else {
        echo "Tipo de archivo no permitido.";
        exit();
    }
} else {
    $sql = "UPDATE promociones SET nombre_promo='$nombre' WHERE id_promo='$id'";
}

if ($con->query($sql) === TRUE) {
    header("Location: promociones_lista.php");
} else {
    echo "Error al actualizar promoción: " . $con->error;
}
?>

