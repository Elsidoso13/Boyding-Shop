<?php
include '../administrador/Menu.php';
$nombre = $codigo = $descripcion = $costo = $stock = $archivo = "";
$nombreErr = $codigoErr = $descripcionErr = $costoErr = $stockErr = $archivoErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $codigo = trim($_POST["codigo"]);
    $descripcion = trim($_POST["descripcion"]);
    $costo = trim($_POST["costo"]);
    $stock = trim($_POST["stock"]);
    $archivo = $_FILES["archivo"]["name"];

    $errors = false;

    if (empty($nombre)) {
        $nombreErr = "El nombre es obligatorio";
        $errors = true;
    }

    if (empty($codigo)) {
        $codigoErr = "El código es obligatorio";
        $errors = true;
    } else {
        $sql_check = "SELECT * FROM productos WHERE CODIGO = $1 AND eliminado = 0";
        $res_check = pg_query_params($con, $sql_check, array($codigo));
        if (pg_num_rows($res_check) > 0) {
            $codigoErr = "El código ya existe";
            $errors = true;
        }
    }

    if (empty($descripcion)) {
        $descripcionErr = "La descripción es obligatoria";
        $errors = true;
    }

    if (!is_numeric($costo) || $costo <= 0) {
        $costoErr = "El costo debe ser un número positivo";
        $errors = true;
    }

    if (!filter_var($stock, FILTER_VALIDATE_INT) || $stock < 0) {
        $stockErr = "El stock debe ser un número entero no negativo";
        $errors = true;
    }

    if (empty($archivo)) {
        $archivoErr = "La imagen es obligatoria";
        $errors = true;
    } else {
        $target_dir = "images/";
        $target_file = $target_dir . basename($archivo);
        if ($_FILES["archivo"]["error"] === UPLOAD_ERR_OK) {
            if (!move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file)) {
                $archivoErr = "Error al subir la imagen.";
                $errors = true;
            }
        } else {
            $archivoErr = "Error al subir el archivo.";
            $errors = true;
        }
    }

    if (!$errors) {
        $sql_insert = "INSERT INTO productos (NOMBRE_PRODUCTO, CODIGO, DESCRIPCION_PRODUCTO, COSTO_PRODUCTO, STOCK, ARCHIVO, STATUS_PRODUCTO, ELIMINADO)
                       VALUES ($1, $2, $3, $4, $5, $6, 1, 0)";
        $params = array($nombre, $codigo, $descripcion, $costo, $stock, $archivo);
        $res_insert = pg_query_params($con, $sql_insert, $params);

        if ($res_insert) {
            header("Location: productos_lista.php");
            exit();
        } else {
            echo "Error: " . pg_last_error($con);
        }
    }
}

pg_close($con);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5F9EA0;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #fff;
        }
        form {
            background-color: #FFF8DC;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input[type="text"], input[type="file"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        button {
            background-color: #2F4F4F;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #3F5F5F;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Agregar Producto</h1>
    <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
        <span class="error"><?php echo $nombreErr; ?></span>

        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" value="<?php echo $codigo; ?>">
        <span class="error"><?php echo $codigoErr; ?></span>

        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" value="<?php echo $descripcion; ?>">
        <span class="error"><?php echo $descripcionErr; ?></span>

        <label for="costo">Costo:</label>
        <input type="number" id="costo" name="costo" step="0.01" value="<?php echo $costo; ?>">
        <span class="error"><?php echo $costoErr; ?></span>

        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $stock; ?>">
        <span class="error"><?php echo $stockErr; ?></span>

        <label for="archivo">Imagen:</label>
        <input type="file" id="archivo" name="archivo">
        <span class="error"><?php echo $archivoErr; ?></span>

        <button type="submit">Agregar Producto</button>

        <a href="productos_lista.php">Regresar al listado</a>
    </form>
</body>
</html>
