<!DOCTYPE html>
<html>

<head>
    <title>Edición de Promociones</title>
    <link rel="stylesheet" type="text/css" href="Estilos/Editar.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#nombre').blur(verificarCodigo);
        });

        function validar() {
            var nombre = $('#nombre').val();
            var foto = $('#foto')[0].files[0];

            if (nombre) {
                var formData = new FormData(document.getElementById('Forma01'));
                if (foto) {
                    formData.append('foto', foto);
                }

                $.ajax({
                    type: "POST",
                    url: 'actualizar_promociones.php',
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

        .h2 {
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

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
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

    <div class="container">
        <?php
        include '../administrador/Menu.php';
        $con = conecta();

        // Validar y escapar el ID recibido
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Consulta parametrizada para evitar inyección SQL
        $sql = "SELECT ID_PROMO, NOMBRE_PROMO, ARCHIVO FROM promociones WHERE ID_PROMO = $1";
        $res = pg_query_params($con, $sql, [$id]);

        if (!$res || pg_num_rows($res) == 0) {
            die("Promoción no encontrada o eliminada.");
        }

        $row = pg_fetch_assoc($res);
        ?>
        <h2>Edición de Promociones</h2>
        <form id="Forma01" action="actualizar_promociones.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['ID_PROMO']); ?>">

            <label for="nombre">Nombre de la Promoción:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row['NOMBRE_PROMO']); ?>" required>

            <label for="archivo">Archivo actual:</label>
            <p>
                <img src="plantillas/<?php echo htmlspecialchars($row['archivo']); ?>" alt="" style="width: 400px; height: 200px;">
            </p>

            <label for="foto">Subir nuevo archivo:</label>
            <input type="file" id="foto" name="foto" accept="image/*">

            <button type="submit">Actualizar Promoción</button>
            <a href="promociones_lista.php">Regresar al listado</a>
        </form>
    </div>
</body>

</html>
