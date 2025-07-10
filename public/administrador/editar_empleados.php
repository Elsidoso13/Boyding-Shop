<?php
include "Menu.php";
require_once "../funciones/conecta.php";
$con = conecta();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Verificar si se recibe el parámetro id en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Si no se recibe un ID válido, redirige a la lista de empleados o muestra un error
    die("ID de empleado no válido.");
}

// Consultar los datos del empleado
$sql = "SELECT * FROM empleados WHERE id = $1";
$result = pg_query_params($con, $sql, array($id));

// Verificar que se encontró el empleado
if (pg_num_rows($result) == 0) {
    die("Empleado no encontrado.");
}

$row = pg_fetch_assoc($result);

$nombre = $row['nombre'];
$apellidos = $row['apellidos'];
$email = $row['correo'];
$pass = $row['pass'];
$rol = $row['rol'];
$foto = $row['archivo_nombre'];
$pdf_cv = $row['archivo_file'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5F9EA0;
            color: #333;
        }
        .form-container {
            margin: 0 auto;
            width: 50%;
            background-color: #FFF8DC;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="file"] {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }
        .submit-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        h2 {
            text-align: center;
            color: black;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Editar Empleado</h2>
    <form action="actualizar_empleados.php" method="POST" enctype="multipart/form-data">
        <!-- Campo oculto para el ID -->
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
        </div>
        <div class="form-group">
            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($apellidos); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label for="pass">Contraseña:</label>
            <input type="password" id="pass" name="pass" value="<?php echo htmlspecialchars($pass); ?>">
        </div>
        <div class="form-group">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="1" <?php if ($rol == 1) echo 'selected'; ?>>Gerente</option>
                <option value="2" <?php if ($rol == 2) echo 'selected'; ?>>Ejecutivo</option>
            </select>
        </div>
        <div class="form-group">
            <label for="foto">Foto (actual: <?php echo $foto ? "Sí" : "No"; ?>):</label>
            <input type="file" id="foto" name="foto">
        </div>
        <div class="form-group">
            <label for="pdf_cv">PDF CV (actual: <?php echo $pdf_cv ? "Sí" : "No"; ?>):</label>
            <input type="file" id="pdf_cv" name="pdf_cv">
        </div>
        <button type="submit" class="submit-btn">Actualizar Empleado</button>
    </form>
    <a href="empleados_lista.php">Regresar al listado</a>
</div>

<script>
        function validateForm() {
        var nombre = document.getElementById('nombre').value;
        var apellidos = document.getElementById('apellidos').value;
        var email = document.getElementById('email').value;
        var pass = document.getElementById('pass').value;
        var rol = document.getElementById('rol').value;
        var foto = document.getElementById('foto').files.length;
        var pdf_cv = document.getElementById('pdf_cv').files.length;

        if (nombre === '' || apellidos === '' || email === '' || pass === '' || rol === '') {
            alert('Por favor, complete todos los campos');
            return false;
        }

        if (foto === 0 && pdf_cv === 0) {
            alert('Por favor, seleccione al menos un archivo para la foto o el pdf_cv');
            return false;
        }
        return true;
    }
</script>
<script>
    function checkEmailAvailability() {
        var email = document.getElementById('email').value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '.verifica_email.php? email=' + email, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = xhr.responseText;
                if (response === 'exist') {
                    alert('El correo electrónico ya existe en la base de datos');
                }
            }
        };
        xhr.send();
    }
</script>
</script>

</body>
</html>
