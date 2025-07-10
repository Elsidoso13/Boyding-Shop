<?php
include '../administrador/Menu.php';
$sql = "SELECT * FROM productos WHERE eliminado = 0";
$res = pg_query($con, $sql);

if (!$res) {
    echo "Error en la consulta: " . pg_last_error($con);
    exit();
}


// Cerrar la conexión
pg_close($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function eliminarProducto(id, row) {
            if (confirm("¿Estás seguro de que deseas eliminar este producto?")) {
                $.ajax(
                    url: 'eliminar_productos.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response === '1') {
                            $(row).closest('tr').fadeOut(500, function() {
                                $(this).remove();
                            });
                        } else {
                             $(row).closest('tr').fadeOut(500, function() {
                                $(this).remove();
                        });
                    },
                    error: function() {
                        alert("Error al realizar la solicitud.");
                    }
                });
            }
        }

    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF8DC;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2F4F4F;
        }
         {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2F4F4F;
            color: white;
        }
        tr:hover {background-color: #f1f1f1;}
        button {
            background-color: #2F4F4F;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #3F5F5F;
        }
    </style>
</head>
<a>
    <h1>Lista de Productos</h1>

    <button onclick="window.location.href='agregar_producto.php'" >Agregar Producto</button>

    <table>
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Imagen</th>
        <th>Acciones</th>
    </tr>
    <?php while ($row = pg_fetch_assoc($res)): ?>
    <tr>
        <td><?php echo $row["id_producto"]; ?></td>
        <td><?php echo $row["nombre_producto"]; ?></td>
        <td><?php echo $row["costo_producto"]; ?></td>
        <td><?php echo $row["stock"]; ?></td>
        <td><img src="images/<?php echo $row["archivo"]; ?>" width="100" /></td>
        <td>
            <button onclick="eliminarProducto(<?php echo $row['id_producto']; ?>, this)">Eliminar</button>
            <form action='editar_producto.php' method='GET' style='display:inline;'>
                <input type='hidden' name='id' value='<?php echo $row['id_producto']; ?>'>
                <button type='submit' class='action-btn'>Editar Producto</button>
            </form>
            <form action='detalle_producto.php' method='GET' style='display:inline;'>
                <input type='hidden' name='id' value='<?php echo $row['id_producto']; ?>'>
                <button type='submit' class='action-btn'>Detalle Producto</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
