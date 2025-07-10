<?php
require "../funciones/conecta.php";
$con = conecta();

// Verifica si el ID está presente en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Obtén el ID de empleado desde el parámetro GET y sanitízalo
    $id = (int)$_GET['id'];

    // Consulta los datos del empleado
    $sql = "SELECT * FROM empleados WHERE id = $id";
    $res = pg_query($con, $sql);

    // Verifica si se encontró un resultado
    if (pg_num_rows($res) > 0) {
        $row = pg_fetch_assoc($res);

        // Asigna los valores del empleado a variables
        $nombre = $row['nombre'];
        $apellidos = $row['apellidos'];
        $email = $row['correo'];
        $rol = $row['rol'];
        $foto = $row['archivo_nombre'];  // Cambiado a `foto` según tu estructura
        $pdf_cv = $row['archivo_file']; // Cambiado a `pdf` según tu estructura

        // Convierte el valor numérico del rol a su representación en texto
        if ($rol == 1) {
            $rol_text = "Gerente";
        } else if ($rol == 2) {
            $rol_text = "Ejecutivo";
        } else {
            $rol_text = "Desconocido";
        }
    } else {
        // Si no se encuentra el empleado, muestra un mensaje
        echo "Empleado no encontrado.";
        exit();
    }
} else {
    // Si no se proporcionó un ID válido, muestra un mensaje
    echo "ID de empleado no válido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Empleado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5F9EA0;
            color: #333;
        }
        .details-container {
            margin: 0 auto;
            width: 50%;
            background-color: #FFF8DC;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .detail {
            margin-bottom: 10px;
        }
        label {
            font-weight: bold;
        }
        .back-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
        .img-container img {
            max-width: 150px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="details-container">
    <h2>Detalles del Empleado</h2>
    
    <div class="detail">
        <label>ID: </label> <?php echo $id; ?>
    </div>
    <div class="detail">
        <label>Nombre: </label> <?php echo $nombre; ?>
    </div>
    <div class="detail">
        <label>Apellidos: </label> <?php echo $apellidos; ?>
    </div>
    <div class="detail">
        <label>Email: </label> <?php echo $email; ?>
    </div>
    <div class="detail">
        <label>Rol: </label> <?php echo $rol_text; ?>
    </div>
    
    <div class="detail img-container">
        <label>Foto: </label>
        <?php if ($foto): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($foto); ?>" alt="Foto del Empleado">
        <?php else: ?>
            <p>No hay foto disponible</p>
        <?php endif; ?>
    </div>

    <div class="detail">
        <label>Currículum (PDF): </label>
        <?php if ($pdf_cv): ?>
            <a href="data:application/pdf;base64,<?php echo base64_encode($pdf_cv); ?>" target="_blank">Ver CV</a>
        <?php else: ?>
            <p>No hay CV disponible</p>
        <?php endif; ?>
    </div>

    <!-- Botón para volver a la lista de empleados -->
    <form action="empleados_lista.php" method="GET">
        <button type="submit" class="back-btn">Volver a la Lista</button>
    </form>
</div>

</body>
</html>
