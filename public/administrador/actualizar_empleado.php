<?php
require "funciones/conecta.php";
$con = conecta();

// Obtener los datos del formulario
$id = $_POST['id'];
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$rol = $_POST['rol']; // 1 para Gerente o 2 para Ejecutivo

// Verificar si se subió una nueva foto
if (isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
    $foto = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    move_uploaded_file($foto_tmp, "../imagenes/$foto");
    $sql = "UPDATE empleados SET archivo_nombre = $1 WHERE id = $2";
    pg_query_params($con, $sql, array($foto, $id));
}

// Verificar si se subió un nuevo CV
if (isset($_FILES['pdf_cv']) && $_FILES['pdf_cv']['name'] != '') {
    $pdf_cv = $_FILES['pdf_cv']['name'];
    $pdf_tmp = $_FILES['pdf_cv']['tmp_name'];
    move_uploaded_file($pdf_tmp, "uploads/cv/$pdf_cv");
    $sql = "UPDATE empleados SET archivo_file = $1 WHERE id = $2";
    pg_query_params($con, $sql, array($pdf_cv, $id));
}

// Verificar si el correo electrónico ya existe en la base de datos
$sql_email = "SELECT * FROM empleados WHERE correo = $1 AND id != $2";
$res_email = pg_query_params($con, $sql_email, array($email, $id));
if (pg_num_rows($res_email) > 0) {
    // Si el correo electrónico ya existe, mostrar un mensaje de error
    echo "El correo electrónico $email ya existe en la base de datos.";
    exit();
} else {
    // Actualizar los demás datos del empleado
    $sql = "UPDATE empleados 
            SET nombre = $1, apellidos = $2, correo = $3, rol = $4 
            WHERE id = $5";
    pg_query_params($con, $sql, array($nombre, $apellidos, $email, $rol, $id));

    // Actualizar la contraseña solo si se ha cambiado
    if ($pass != '') {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
        $sql_pass = "UPDATE empleados SET pass = $1 WHERE id = $2";
        pg_query_params($con, $sql_pass, array($hashed_pass, $id));
    }

    // Redirigir de vuelta a la lista de empleados
    header("Location: empleados_lista.php");
    exit();
}
?>
