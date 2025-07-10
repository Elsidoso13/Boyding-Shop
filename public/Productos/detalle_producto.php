<?php
require "/funciones/conecta.php";
$con = conecta();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener los detalles del producto
$sql = "SELECT * FROM productos WHERE id_producto = $id AND eliminado = 0";
$res = $con->query($sql);

if ($res->num_rows == 0) {
    die("Producto no encontrado o eliminado.");
}

$row = $res->fetch_assoc();

include '../Menu.php';
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
            <label>ID del Producto:</label>
            <span><?php echo $row['id_producto']; ?></span>
        </div>
        
        <div class="field">
            <label>Código:</label>
            <span><?php echo $row['codigo ']; ?></span>
        </div>
        
        <div class="field">
            <label>Nombre:</label>
            <span><?php echo $row['nombre_producto']; ?></span>
        </div>
        
        <div class="field">
            <label>Descripción:</label>
            <span><?php echo $row['descripcion_producto ']; ?></span>
        </div>
        
        <div class="field">
            <label>Costo:</label>
            <span>$<?php echo number_format($row['costo_producto'], 2); ?></span>
        </div>
        
        <div class="field">
            <label>Stock:</label>
            <span><?php echo $row['stock']; ?></span>
        </div>
        
        <div class="field">
            <label>Imagen:</label>
            <?php if ($row['archivo']): ?>
                <img src="/public/Productos/images/<?php echo $row['archivo']; ?>" alt="Imagen del Producto">
            <?php else: ?>
                <span>No disponible</span>
            <?php endif; ?>
        </div>
        
        <div class="button-container">
            <button onclick="window.location.href='../index.php'">Regresar al inicio</button>
            <button onclick="window.location.href='../public/pedidos/pedidos_lista.php'">Agregar al carrito</button>
        </div>
    </div>
</body>
</html>
