<?php
session_start();
require '../funciones/conecta.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $con = conecta();
    
    try {
        // Sanitizar datos de entrada
        $nombre = trim($_POST['nombre']);
        $apellidos = trim($_POST['apellidos']);
        $correo = trim($_POST['correo']);
        $password = password_hash(trim($_POST['pass']), PASSWORD_BCRYPT);
        
        // Validar campos requeridos
        if (empty($nombre) || empty($apellidos) || empty($correo) || empty($_POST['pass'])) {
            throw new Exception("Todos los campos son requeridos");
        }
        
        // Validar formato de correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El formato del correo no es válido");
        }
        
        // Verificar si el correo ya existe
        $stmt_check = $con->prepare("SELECT COUNT(*) FROM clientes WHERE correo = ? AND eliminado = 0");
        $stmt_check->execute([$correo]);
        $count = $stmt_check->fetchColumn();
        
        if ($count > 0) {
            throw new Exception("Este correo ya está registrado");
        }
        
        // Insertar nuevo usuario usando prepared statement
        $stmt = $con->prepare("INSERT INTO clientes (nombre, apellidos, correo, pass, eliminado) VALUES (?, ?, ?, ?, 0)");
        $stmt->execute([$nombre, $apellidos, $correo, $password]);
        
        // Obtener el ID del usuario recién insertado
        $user_id = $con->lastInsertId();
        
        // Establecer la sesión del usuario después del registro exitoso
        $_SESSION['correo'] = $correo;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['id'] = $user_id;
        
        // Para debugging (remover en producción)
        var_dump($_SESSION); 
        
        header('Location: ../   index.php'); // Redirigir al índice
        exit();
        
    } catch (PDOException $e) {
        // Error de base de datos
        error_log("Error de base de datos: " . $e->getMessage());
        echo "Error al registrar el usuario. Por favor, inténtelo de nuevo.";
        
    } catch (Exception $e) {
        // Error de validación
        echo "Error: " . $e->getMessage();
    }
    
    // No es necesario cerrar la conexión PDO explícitamente
    $con = null;
    
} else {
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Método no permitido']);
}
?>