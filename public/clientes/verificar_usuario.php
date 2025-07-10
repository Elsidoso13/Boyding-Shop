<?php
session_start();
require '../funciones/conecta.php';

// Configurar el header para respuesta JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $con = conecta();
    
    try {
        // Sanitizar datos de entrada
        $correo = trim($_POST['email']);
        $password = trim($_POST['password']);
        
        // Validar campos requeridos
        if (empty($correo) || empty($password)) {
            echo json_encode(['error' => 'Todos los campos son obligatorios']);
            exit();
        }
        
        // Validar formato de correo
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => 'El formato del correo no es válido']);
            exit();
        }
        
        // Buscar usuario en la base de datos
        $stmt = $con->prepare("SELECT id, nombre, apellidos, correo, pass FROM clientes WHERE correo = ? AND eliminado = 0");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            // Verificar la contraseña
            if (password_verify($password, $usuario['pass'])) {
                // Contraseña correcta - establecer sesión
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['apellidos'] = $usuario['apellidos'];
                $_SESSION['id'] = $usuario['id'];
                
                // Respuesta exitosa
                echo json_encode([
                    'existe' => true,
                    'usuario' => [
                        'id' => $usuario['id'],
                        'nombre' => $usuario['nombre'],
                        'apellidos' => $usuario['apellidos'],
                        'correo' => $usuario['correo']
                    ]
                ]);
            } else {
                // Contraseña incorrecta
                echo json_encode(['error' => 'Contraseña incorrecta']);
            }
        } else {
            // Usuario no encontrado
            echo json_encode(['error' => 'Usuario no encontrado o inactivo']);
        }
        
    } catch (PDOException $e) {
        // Error de base de datos
        error_log("Error de base de datos en verificar_usuario.php: " . $e->getMessage());
        echo json_encode(['error' => 'Error en el servidor. Por favor, inténtelo de nuevo.']);
        
    } catch (Exception $e) {
        // Error general
        error_log("Error general en verificar_usuario.php: " . $e->getMessage());
        echo json_encode(['error' => 'Error inesperado. Por favor, inténtelo de nuevo.']);
    }
    
    // Cerrar conexión
    $con = null;
    
} else {
    // Método no permitido
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>
