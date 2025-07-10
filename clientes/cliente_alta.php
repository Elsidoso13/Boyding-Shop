<?php
include "../administrador/Menu.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regístrese</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5F9EA0;
            margin: 0;
            padding: 0;
        }
        .form-container {
            margin: 50px auto;
            width: 50%;
            background-color: #FFF8DC;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .submit-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
        .submit-btn:hover {
            background-color: #45a049;
        }
        #mensaje, #mensajeCorreo {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        .h2{
            color: white;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Regístrese y sea bienvenido a nuestra familia canina</h2>
        <form id="clienteForm" action="guardar_usuario.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>
                <div id="mensajeCorreo"></div>
            </div>
            <div class="form-group">
                <label for="pass">Contraseña:</label>
                <input type="password" id="pass" name="pass" required>
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">Registrar</button>
            </div>
            <div id="mensaje"></div>
        </form>
    </div>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
    function validarCorreo() {
        var correo = $('#correo').val();
        $.ajax({
            url: 'validar_correo.php',
            type: 'POST',
            dataType: 'json',
            data: { correo: correo },
            success: function(response) {
                console.log(response); 
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

    $(document).ready(function() {
        $('#clienteForm').on('submit', function(e) {
            let errorMessage = '';
            if ($('#nombre').val() === '' || $('#apellidos').val() === '' || $('#correo').val() === '' || $('#pass').val() === '') {
                e.preventDefault();  
                errorMessage = 'Faltan campos por llenar.';
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