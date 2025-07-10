<?PHP
include '../administrador/Menu.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Alta de Promociones</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#nombre').blur(verificarNombre);
        });

        function validar() {
            var nombre = $('#nombre').val();
            var archivo = $('#archivo')[0].files[0];

            if (nombre && archivo) {
                var formData = new FormData();
                formData.append('nombre', nombre);
                formData.append('archivo', archivo);

                $.ajax({
                    type: "POST",
                    url: 'guardar_promociones.php',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        window.location.href = 'promociones_lista.php';
                    }
                });
            } else {
                $('#mensaje').html('Faltan campos por llenar');
                setTimeout(function () { $('#mensaje').empty(); }, 5000);
            }
        }
    </script>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF8DC;
        }
        h1{
            text-align: center;
            color: #2F4F4F;
        }
        form {
            margin: 20px auto;
            width: 50%;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="number"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            height: 100px;
            resize: vertical;
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
<h1>Alta de Promociones</h1>

<form action="guardar_promociones.php" method="POST" enctype="multipart/form-data">
    
    <input type="text" name="nombre" id="nombre" placeholder="Nombre" /> <br>
    <div id="mensajeNombre" style="display: inline-block; color: red;"></div> <br>
    <input type="file" name="archivo" id="archivo" required />
    <div id="mensaje"></div>
    <button type="submit" onclick="validar(); return false" > Guardar </button>
</form>

<a href="promociones_lista.php" class="boton-regresar">REGRESAR</a>
<body></body>