<!DOCTYPE html>
<html>

<head>
    <title>Lista de Promociones</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function eliminarPromocion(id) {
            if (confirm("¿Estás seguro de que quieres eliminar esta promoción?")) {
                $.ajax({
                    url: 'Promociones_Elimina.php',
                    type: 'POST',
                    data: { id: id },
                    success: function (response) {
                        if (response == 1) {
                            $("#fila-" + id).remove();
                        } else {
                            alert("Hubo un error al eliminar la promoción.");
                        }
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

        h2 {
            text-align: center;
            color: #2F4F4F;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2F4F4F;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

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

<body>
    <?php
    include '../administrador/Menu.php';
 // Archivo de conexión a PostgreSQL
    $con = conecta();

    // Consulta adaptada a PostgreSQL
    $sql = "SELECT ID_PROMO, NOMBRE_PROMO, ARCHIVO FROM Promociones WHERE ELIMINADO = 0";
    $res = pg_query($con, $sql);

    if (!$res) {
        die("Error en la consulta: " . pg_last_error());
    }
    ?>

    <h2>Lista de Promociones</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Archivo</th>
            <th>Acciones</th>
        </tr>

        <?php
        while ($row = pg_fetch_assoc($res)) {
            $id = $row["id_promo"];
            $nombre = $row["nombre_promo"];
            $archivo = $row["archivo"];
        ?>
            <tr id="fila-<?php echo $id; ?>">
                <td><?php echo $id; ?></td>
                <td><?php echo $nombre; ?></td>
                <td><img src="plantillas/<?php echo $archivo; ?>" width="400" /></td>
                <td>
                    <button onclick="eliminarPromocion(<?php echo $id; ?>)" class="boton eliminar">Eliminar Promoción</button>
                    <form action='editar_promociones.php' method='GET' style='display:inline;'>
                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                        <button type='submit' class='action-btn'>Editar Producto</button>
                    </form>
                    <form action='detalle_promociones.php' method='GET' style='display:inline;'>
                        <input type='hidden' name='id' value='<?php echo $id; ?>'>
                        <button type='submit' class='action-btn'>Detalle Producto</button>
                    </form>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
    <button onclick="window.location.href='alta_promociones.php'">Nueva Promoción</button>
</body>

</html>
