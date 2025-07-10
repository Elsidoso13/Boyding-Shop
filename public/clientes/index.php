<!DOCTYPE html>
<html lang="es">
<head>
    <title>Iniciar Sesión / Registro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #5F9EA0;
            color: #333;
            margin: 0;
            padding: 20px;
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
            margin-bottom: 30px;
        }
        .tab-container {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid #2F4F4F;
        }
        .tab {
            flex: 1;
            padding: 15px;
            text-align: center;
            background-color: #E6E6FA;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;
            color: #2F4F4F;
            font-size: 16px;
        }
        .tab.active {
            background-color: #2F4F4F;
            color: white;
        }
        .tab:hover {
            background-color: #5F9EA0;
            color: white;
        }
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #2F4F4F;
        }
        input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: #5F9EA0;
            box-shadow: 0 0 5px rgba(95, 158, 160, 0.3);
        }
        button {
            background-color: #2F4F4F;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #5F9EA0;
        }
        .mensaje {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            display: none;
        }
        .mensaje.error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef5350;
        }
        .mensaje.success {
            background-color: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #4caf50;
        }
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Bienvenido</h1>
    
    <div class="tab-container">
        <button class="tab active" onclick="showForm('login')">Iniciar Sesión</button>
        <button class="tab" onclick="showForm('register')">Registrarse</button>
    </div>

    <!-- Formulario de Inicio de Sesión -->
    <div id="login-form" class="form-container active">
        <form id="login">
            <label for="login-email">Email:</label>
            <input type="email" id="login-email" name="email" required>
            
            <label for="login-password">Contraseña:</label>
            <input type="password" id="login-password" name="password" required>
            
            <button type="submit">Iniciar Sesión</button>
        </form>
        <div id="login-mensaje" class="mensaje"></div>
    </div>

    <!-- Formulario de Registro -->
    <div id="register-form" class="form-container">
        <form id="register">
            <label for="register-nombre">Nombre:</label>
            <input type="text" id="register-nombre" name="nombre" required>
            
            <label for="register-apellidos">Apellidos:</label>
            <input type="text" id="register-apellidos" name="apellidos" required>
            
            <label for="register-correo">Correo electrónico:</label>
            <input type="email" id="register-correo" name="correo" required>
            
            <label for="register-pass">Contraseña:</label>
            <input type="password" id="register-pass" name="pass" required>
            <div class="password-requirements">
                La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números.
            </div>
            
            <label for="register-confirm-password">Confirmar contraseña:</label>
            <input type="password" id="register-confirm-password" name="confirm_password" required>
            
            <button type="submit">Registrarse</button>
        </form>
        <div id="register-mensaje" class="mensaje"></div>
    </div>
</div>

<script>
    // Función para cambiar entre formularios
    function showForm(formType) {
        // Ocultar todos los formularios
        document.querySelectorAll('.form-container').forEach(container => {
            container.classList.remove('active');
        });
        
        // Quitar clase active de todas las pestañas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Mostrar el formulario seleccionado
        document.getElementById(formType + '-form').classList.add('active');
        
        // Activar la pestaña correspondiente
        event.target.classList.add('active');
        
        // Limpiar mensajes
        document.querySelectorAll('.mensaje').forEach(mensaje => {
            mensaje.style.display = 'none';
        });
    }

    // Función para mostrar mensajes
    function showMessage(elementId, message, type = 'error') {
        const messageElement = document.getElementById(elementId);
        messageElement.textContent = message;
        messageElement.className = `mensaje ${type}`;
        messageElement.style.display = 'block';
        
        setTimeout(() => {
            messageElement.style.display = 'none';
        }, 4000);
    }

    // Validación de contraseña
    function validatePassword(password) {
        const minLength = 8;
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);
        const hasNumbers = /\d/.test(password);
        
        return password.length >= minLength && hasUpperCase && hasLowerCase && hasNumbers;
    }

    // Manejo del formulario de inicio de sesión
    document.getElementById('login').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        
        if (!email || !password) {
            showMessage('login-mensaje', 'Todos los campos son obligatorios');
            return;
        }
        
        // Crear FormData para enviar datos al servidor
        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);
        
        // Realizar petición AJAX al servidor
        fetch('verificar_usuario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showMessage('login-mensaje', data.error);
            } else if (data.existe) {
                showMessage('login-mensaje', '¡Inicio de sesión exitoso! Redirigiendo...', 'success');
                setTimeout(() => {
                    window.location.href = 'bienvenido.php';
                }, 1500);
            } else {
                showMessage('login-mensaje', 'Usuario no encontrado o inactivo');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('login-mensaje', 'Error en la conexión con el servidor');
        });
    });

    // Manejo del formulario de registro
    document.getElementById('register').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nombre = document.getElementById('register-nombre').value;
        const apellidos = document.getElementById('register-apellidos').value;
        const correo = document.getElementById('register-correo').value;
        const pass = document.getElementById('register-pass').value;
        const confirmPassword = document.getElementById('register-confirm-password').value;
        
        // Validaciones del lado del cliente
        if (!nombre || !apellidos || !correo || !pass || !confirmPassword) {
            showMessage('register-mensaje', 'Todos los campos son obligatorios');
            return;
        }
        
        if (pass !== confirmPassword) {
            showMessage('register-mensaje', 'Las contraseñas no coinciden');
            return;
        }
        
        if (!validatePassword(pass)) {
            showMessage('register-mensaje', 'La contraseña no cumple con los requisitos mínimos');
            return;
        }
        
        // Validar formato de correo
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(correo)) {
            showMessage('register-mensaje', 'El formato del correo no es válido');
            return;
        }
        
        // Crear FormData para enviar datos al servidor
        const formData = new FormData();
        formData.append('nombre', nombre);
        formData.append('apellidos', apellidos);
        formData.append('correo', correo);
        formData.append('pass', pass);
        
        // Mostrar mensaje de carga
        showMessage('register-mensaje', 'Procesando registro...', 'success');
        
        // Realizar petición AJAX al servidor
        fetch('guardar_usuario.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Verificar si la respuesta es exitosa
            if (response.ok) {
                // Si la respuesta es exitosa, el usuario fue registrado y redirigido
                // Esto significa que se creó la sesión correctamente
                showMessage('register-mensaje', '¡Registro exitoso! Redirigiendo al sitio principal...', 'success');
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 2000);
            } else {
                // Si hay error HTTP, intentar leer el mensaje de error
                return response.text().then(text => {
                    throw new Error(text || 'Error en el servidor');
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Mostrar el mensaje de error específico del servidor
            let errorMessage = error.message;
            
            // Verificar si el error contiene mensajes específicos
            if (errorMessage.includes('Este correo ya está registrado')) {
                showMessage('register-mensaje', 'Este correo ya está registrado. Intenta con otro correo.');
            } else if (errorMessage.includes('Todos los campos son requeridos')) {
                showMessage('register-mensaje', 'Todos los campos son requeridos');
            } else if (errorMessage.includes('El formato del correo no es válido')) {
                showMessage('register-mensaje', 'El formato del correo no es válido');
            } else {
                showMessage('register-mensaje', 'Error al registrar el usuario. Por favor, inténtelo de nuevo.');
            }
        });
    });
</script>

</body>
</html>
