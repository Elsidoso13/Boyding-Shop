<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/funciones/conecta.php';
$con = conecta();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica si la variable 'id' se pasa por GET, POST o SESSION
$id = $_GET['id'] ?? $_POST['id'] ?? $_SESSION['id'] ?? null;

// Sanitización de la variable para consultas SQL (si se utiliza)
if ($id !== null && !is_numeric($id)) {
    $id = intval($id); // Convierte a entero
}

// Verifica si el usuario está autenticado
$is_authenticated = false;
if (isset($_SESSION['correo'])) {
    $correo = $_SESSION['correo'];

    // Consulta para verificar el usuario en la base de datos
    $query = "SELECT id, nombre, apellidos FROM clientes WHERE correo = $1";
    $result = pg_query_params($con, $query, [$correo]);

    if ($result && pg_num_rows($result) > 0) {
        $user_data = pg_fetch_assoc($result);
        $is_authenticated = true;
        $_SESSION['nombre'] = $user_data['nombre'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        h1 {
            text-align: center;
            color: #FFEBCD;
        }

        h2 {
            text-align: center;
        }

        .container {
            background-color: #2F4F4F;
            border-radius: 3px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .button-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
        }

        form {
            width: 45%; /* Ajuste para que quepan dos formularios en cada lado */
            display: flex;
            justify-content: center;
        }

        button {
            background-color: #2F4F4F;
            color: white; 
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #006B6B;
        }

        .logout-button {
            margin-left: auto;
        }

        .file-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 50px;
        }

        .center-logo {
            text-align: center;
        }

        .welcome-message {
            text-align: center;
            color: #FFF;
            font-size: 1.5em; /* Opcional: ajusta el tamaño de la fuente */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo central -->
        <div class="center-logo">
            <a href="../index.php">
                <img src="/public/administrador/SRC2.png" width="10%" alt="Inicio">
            </a>
        </div>

        <!-- Botones en una fila -->
        <div class="button-row">
            <form action="../public/administrador/index.php" method="GET" class="logout-button">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <button type="submit">Iniciar sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
