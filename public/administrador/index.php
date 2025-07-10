<!DOCTYPE html>
<html lang="es">
<head>
    <title>Iniciar Sesión</title>
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
            color: #2F4F4F;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #2F4F4F;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #5F9EA0;
        }
        #mensaje {
            color: red;
            margin-top: 10px;
        }
    </style>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#login').submit(function(e){
                e.preventDefault(); 

                var email = $('#email').val(); 
                var password = $('#password').val();

                if(email === '' || password === ''){
                    $('#mensaje').html('<p>Todos los campos son obligatorios</p>').fadeIn().delay(3000).fadeOut();
                } else {
                    $.ajax({
                        type: 'POST',
                        url: 'verificar_usuario.php',
                        data: {email: email, password: password},
                        dataType: 'json',
                        success: function(response){
                            console.log(response); 
                            if(response.error) {
                                $('#mensaje').html('<p>' + response.error + '</p>').fadeIn().delay(3000).fadeOut();
                            } else if(response.existe) {
                                window.location.href = ' bienvenido.php';
                            } else {
                                $('#mensaje').html('<p>Usuario no encontrado o inactivo</p>').fadeIn().delay(3000).fadeOut();
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#mensaje').html('<p>Error en la solicitud</p>').fadeIn().delay(3000).fadeOut();
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>

<div class="container">
    <h1>Iniciar Sesión</h1>
    <form id="login">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" >
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" ss>
        
        <button type="submit">Iniciar Sesión</button>
    </form>
    <div id="mensaje"></div>
</div>

</body>
</html>
