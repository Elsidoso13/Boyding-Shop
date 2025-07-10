<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../funciones/conecta.php";
    $con = conecta();

    // Obtener y sanitizar datos de entrada
    $email = $_POST['email'];
    $password = md5($_POST['password']); // La contraseña debe estar encriptada como md5 previamente

    // Validar que los parámetros no estén vacíos
    if (empty($email) || empty($password)) {
        echo json_encode(['error' => 'Email o contraseña no pueden estar vacíos']);
        exit();
    }

    // Consulta parametrizada para mayor seguridad
    $sql = "SELECT * FROM empleados WHERE correo = $1 AND pass = $2 AND eliminado = 0";
    $res = pg_query_params($con, $sql, array($email, $password));

    if (!$res) {
        echo json_encode(['error' => pg_last_error($con)]);
        exit();
    }

    if (pg_num_rows($res) > 0) {
        // Obtener los datos del usuario
        $usuario = pg_fetch_assoc($res);
        
        // Iniciar sesión
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellidos'];

        // Respuesta de éxito
        echo json_encode(['existe' => true]);
    } else {
        // Usuario no encontrado o inactivo
        echo json_encode(['error' => 'Usuario no encontrado o inactivo']);
    }

    // Cerrar conexión
    pg_close($con);
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>
