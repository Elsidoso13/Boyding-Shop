<?php 
include '../funciones/conecta.php';
include ' Menu.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta de Empleado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5F9EA0;
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #FFF8DC;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #5F9EA0;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4CAF50;
        }
        .nuevo-listado {
            text-align: right;
            margin-bottom: 20px;
        }
        .nuevo-listado a {
            color: #5F9EA0;
            text-decoration: none;
        }
        .error-message, .email-error-message {
            color: red;
            font-size: 0.9em;
            display: none;
        }
        #mensaje {
            color: red;
            display: none;
        }
        .botones {
            text-align: right;
        }
    </style>
    <script src="jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="container">
    <h1>Registrar Nuevo Empleado</h1>
    <div class="nuevo-listado">
        <a href="empleados_lista.php">Regresar al listado</a>
    </div>

    <form id="empleadoForm" action="guardar_empleados.php" method="POST" enctype="multipart/form-data">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellidos">Apellidos</label>
        <input type="text" id="apellidos" name="apellidos" required>

        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" onblur="validarCorreo()" required>
        <div id="mensaje" class="email-error-message"></div>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>

        <label for="rol">Rol:</label>
        <select id="rol" name="rol" required>
            <option value="0">0 - Seleccionar</option>
            <option value="1">1 - Gerente</option>
            <option value="2">2 - Ejecutivo</option>
        </select>

        <label for="foto">Foto (obligatoria para nuevos empleados)</label>
        <input type="file" id="foto" name="foto" accept="image/*" required>

        <button type="submit">Registrar Empleado</button>
    </form>

    <div class="error-message"></div>
</div>

<script>
// Validar correo mediante AJAX
function validarCorreo() {
    var correo = $('#correo').val();
    $.ajax({
        url: 'validar_correo.php',
        type: 'POST',
        dataType: 'json',
        data: { correo: correo },
        success: function(response) {
            if (response.existe) {
                $('#mensaje').text(response.mensaje).show();
                setTimeout(function() {
                    $('#mensaje').fadeOut('slow');
                }, 5000);
                $('#correo').val(""); 
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
        }
    });
}

// Validar formulario antes de enviar
$(document).ready(function() {
    $('#empleadoForm').on('submit', function(e) {
        let errorMessage = '';
        if ($('#rol').val() === '0') {
            e.preventDefault();
            errorMessage = 'Selecciona un rol.';
            $('.error-message').text(errorMessage).fadeIn();
            setTimeout(function() {
                $('.error-message').fadeOut();
            }, 5000);
        }
    });
});
</script>
</body>
</html>
