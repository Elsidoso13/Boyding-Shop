<?php
require "../funciones/conecta.php"; // Archivo para conectar con PostgreSQL
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
$nombre_usuario = '';

if ($id !== null) {
    // Consulta para verificar el usuario en la base de datos usando el ID
    $query = "SELECT id, nombre, apellidos FROM empleados WHERE id = $1";
    $result = pg_query_params($con, $query, [$id]);

    if ($result && pg_num_rows($result) > 0) {
        $user_data = pg_fetch_assoc($result);
        $is_authenticated = true;
        $_SESSION['nombre'] = $user_data['nombre'];
        $nombre_usuario = $user_data['nombre'];
    }
}

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
    <title>Bienvenido</title>
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
        h2 {
            text-align: center;
            color: #333;
        }
        h3 {
            text-align: center;
            color: #000000;
            text-shadow: #fff ;
        }
        .table-container {
            margin: 0 auto;
            width: 80%;
            background-color: #FFF8DC;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            margin-top: 20px;
        }
        button {
            background-color: #008B8B;
            color: white; 
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 200px;
        }
        button:hover {
            background-color: #006B6B;
        }
    </style>
</head>
<body>
<h1>Bienvenido al Panel de administrador</h1>
<h3><?php echo htmlspecialchars($nombre_usuario); ?></h3>

<div class="table-container">
    <h2>Ingrese la ventana por visitar</h2>
    
    <div class="button-container">
        <form action="empleados_lista.php" method="GET">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">Empleados</button>
        </form>
        
        <form action="../Productos/productos_lista.php" method="GET">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">Productos</button>
        </form>

        <form action="../Promociones/promociones_lista.php" method="GET">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">Promociones</button>
        </form>

        <form action="../pedidos/pedidos_lista.php" method="GET">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">Pedidos</button>
        </form>

        <form action="index.php" method="GET">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit">Cerrar Sesión</button>
        </form>
    </div>
</div>

</body>
</html>
