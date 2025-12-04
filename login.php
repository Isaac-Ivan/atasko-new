<?php require 'includes/header.php'; ?>

    <div class="login-wrapper">
        <div class="caja-login">
            
            <div class="toggle-caja">
                <button id="btn-login" class="btn-toggle activo" onclick="toggleForm('login')">Iniciar Sesión</button>
                <button id="btn-registro" class="btn-toggle" onclick="toggleForm('registro')">Registrarse</button>
            </div>

            <form id="form-login" class="formulario-acceso">
                <div id="login-error" class="error-mensaje"></div>
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="login-email" placeholder="Correo Electrónico" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="login-password" placeholder="Contraseña" required>
                </div>
                
                <button type="submit" class="boton-enviar">Entrar</button>
            </form>

            <form id="form-registro" class="formulario-acceso" style="display: none;">
                <div id="register-message" class="mensaje-registro"></div>
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="register-nombre" placeholder="Nombre Completo" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="register-email" placeholder="Correo Electrónico" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="register-password" placeholder="Contraseña" required>
                </div>
                
                <button type="submit" class="boton-enviar">Crear Cuenta</button>
            </form>

        </div>
    </div>

    <script>
        // --- Lógica para alternar formularios ---
        const formLogin = document.getElementById("form-login");
        const formRegistro = document.getElementById("form-registro");
        const btnLogin = document.getElementById("btn-login");
        const btnRegistro = document.getElementById("btn-registro");

        function toggleForm(formName) {
            // Limpiar mensajes al cambiar de formulario
            document.getElementById('login-error').style.display = 'none';
            document.getElementById('register-message').style.display = 'none';

            if (formName === 'registro') {
                formLogin.style.display = "none";
                formRegistro.style.display = "block";
                formRegistro.style.animation = "fadeIn 0.5s";
                
                btnLogin.classList.remove("activo");
                btnRegistro.classList.add("activo");
            } else {
                formRegistro.style.display = "none";
                formLogin.style.display = "block";
                formLogin.style.animation = "fadeIn 0.5s";
                
                btnRegistro.classList.remove("activo");
                btnLogin.classList.add("activo");
            }
        }

        // --- Lógica para el envío del formulario de Login ---
        const loginForm = document.getElementById('form-login');
        const loginErrorDiv = document.getElementById('login-error');

        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;
            loginErrorDiv.textContent = '';
            loginErrorDiv.style.display = 'none';

            fetch('api/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ correo: email, password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'index.php';
                } else {
                    loginErrorDiv.textContent = data.message;
                    loginErrorDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error en login:', error);
                loginErrorDiv.textContent = 'Ocurrió un error. Intente de nuevo.';
                loginErrorDiv.style.display = 'block';
            });
        });

        // --- Lógica para el envío del formulario de Registro ---
        const registerForm = document.getElementById('form-registro');
        const registerMessageDiv = document.getElementById('register-message');

        registerForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const nombre = document.getElementById('register-nombre').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            registerMessageDiv.textContent = '';
            registerMessageDiv.style.display = 'none';

            fetch('api/register.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ nombre: nombre, correo: email, password: password })
            })
            .then(response => response.json())
            .then(data => {
                registerMessageDiv.textContent = data.message;
                if (data.status === 'success') {
                    registerMessageDiv.className = 'mensaje-registro exito';
                    registerForm.reset(); // Limpiar el formulario
                    setTimeout(() => toggleForm('login'), 2000); // Volver al login después de 2 seg
                } else {
                    registerMessageDiv.className = 'mensaje-registro error';
                }
                registerMessageDiv.style.display = 'block';
            })
            .catch(error => {
                console.error('Error en registro:', error);
                registerMessageDiv.textContent = 'Ocurrió un error. Intente de nuevo.';
                registerMessageDiv.className = 'mensaje-registro error';
                registerMessageDiv.style.display = 'block';
            });
        });
    </script>

</body>
</html>