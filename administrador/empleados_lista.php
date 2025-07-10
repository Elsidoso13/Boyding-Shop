<?php
include '../administrador/Menu.php';
$con = conecta(); 

$sql = "SELECT * FROM empleados WHERE eliminado = 0";
$res = pg_query($con, $sql);

if (!$res) {
    echo "Error al realizar la consulta: " . pg_last_error($con);
    exit();
}

$empleados = pg_num_rows($res); // Total de empleados
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados</title>
    <style>
        body {
                font-family: Arial, sans-serif;
                background-color: #5F9EA0;
                color: #333;
            }
            h1 {
                text-align: center;
                color: #fff;
            }
            .table-container {
                margin: 0 auto;
                width: 80%;
                background-color: #FFF8DC;
                border-radius: 8px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                padding: 12px;
                border-bottom: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #008B8B;
                color: white;
            }
            tr:hover {
                background-color: #f1f1f1;
            }
            button{
                background-color: #008B8B;
                color: white; 
            }
            a{
                background-color: #008B8B;
                color: white;  
            }
    </style>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
        var totalEmpleados = <?php echo $empleados; ?>;

        function actualizarNumeroEmpleados() {
            $('#total-empleados').text(totalEmpleados);
        }

        function confirmarEliminacion(idEmpleado) {
            if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
                $.ajax({
                    url: "eliminar_empleados.php",
                    type: 'POST',
                    data: { id: idEmpleado },
                    dataType: 'text',
                    success: function(res) {
                        if (res == '0') {
                            $('#fila-' + idEmpleado).remove();
                            totalEmpleados--;
                            actualizarNumeroEmpleados();
                            $('#mensaje').html('Empleado eliminado exitosamente.');
                        } else {
                            $('#mensaje').html('Error al eliminar el empleado.');
                        }
                        setTimeout(function() {
                            $('#mensaje').html('');
                        }, 5000);
                    },
                    error: function() {
                        $('#mensaje').html('Error en la solicitud AJAX.');
                        setTimeout(function() {
                            $('#mensaje').html('');
                        }, 5000);
                    }
                });
            }
        }
    </script>
</head>
<body>
<h1>Listado de Empleados</h1>
<div class="table-container">
    <form action="empleados_alta.php" method="GET" style="display:inline;">
        <button type="submit" class="create-btn">Crear Nuevo Empleado</button>
    </form>
    <div id="mensaje"></div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = pg_fetch_assoc($res)) {
                $id = $row["id"];
                $nombre = $row["nombre"];
                $apellidos = $row["apellidos"];
                $email = $row["correo"];
                $rol = $row["rol"];

                $rol_text = ($rol == 1) ? "Gerente" : (($rol == 2) ? "Ejecutivo" : "Desconocido");

                echo "<tr id='fila-$id'>";
                echo "<td>$id</td>";
                echo "<td>$nombre</td>";
                echo "<td>$apellidos</td>";
                echo "<td>$email</td>";
                echo "<td>$rol_text</td>";
                echo "<td>";
                echo "<button type='button' onclick='confirmarEliminacion($id)'>Eliminar</button>";
                echo "<form action='editar_empleados.php' method='GET' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='$id'>";
                echo "<button type='submit'>Editar</button>";
                echo "</form>";
                echo "<form action='empleado_detalle.php' method='GET' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='$id'>";
                echo "<button type='submit'>Ver detalle</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>
