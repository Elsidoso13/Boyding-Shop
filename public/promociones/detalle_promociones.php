<?php
include '../administrador/Menu.php';
$con = conecta();  // Asegúrate de que la función conecta_pg() esté configurada para PostgreSQL

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener los detalles de la promoción
$sql = "SELECT * FROM promociones WHERE id_promo = $1 AND eliminado = 0";
$res = pg_query_params($con, $sql, [$id]);

if (!$res || pg_num_rows($res) == 0) {
    die("Promoción no encontrada o eliminada.");
}

$row = pg_fetch_assoc($res);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF8DC;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #2F4F4F;
        }
        .field {
            margin-bottom: 15px;
        }
        .field label {
            font-weight: bold;
        }
        .field span {
            display: block;
            margin-top: 5px;
            font-size: 1.1em;
        }
        .field img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button-container button {
            background-color: #2F4F4F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-container button:hover {
            background-color: #3F5F5F;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalle del Producto</h1>
        
        <div class="field">
            <label>ID de la promoción:</label>
            <span><?php echo $row['id_promo']; ?></span>
        </div>
        
        <div class="field">
            <label>Nombre:</label>
            <span><?php echo $row['nombre_promo']; ?></span>
        </div>
        
        <div class="field">
            <label>Imagen:</label>
            <?php if ($row['archivo']): ?>
                <img src="plantillas/<?php echo $row['archivo']; ?>" alt="Imagen del Producto">
            <?php else: ?>
                <span>No disponible</span>
            <?php endif; ?>
        </div>
        
        <div class="button-container">
            <button onclick="window.location.href='promociones_lista.php'">Regresar a la Lista</button>
        </div>
    </div>
</body>
</html>
