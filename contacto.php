<?php require 'includes/header.php'; ?>

    <section class="hero hero-interno">
        <div class="hero-overlay"></div>
        <div class="contenedor contenido-hero">
            <h2>Contáctanos</h2>
            <p>¿Tienes dudas o sugerencias? Estamos aquí para escucharte.</p>
        </div>
    </section>

    <section class="seccion contacto">
        <div class="contenedor grid-contacto">
            
            <div class="info-contacto">
                <h3>Ponte en contacto</h3>
                <p class="intro-contacto">Nos encantaría saber de ti. Llena el formulario o visítanos.</p>
                
                <div class="detalle-contacto">
                    <div class="item-contacto">
                        <i class="fa-solid fa-location-dot"></i>
                        <div>
                            <h4>Ubicación</h4>
                            <p>Av. Siempre Viva, CDMX</p>
                        </div>
                    </div>
                    <div class="item-contacto">
                        <i class="fa-solid fa-phone"></i>
                        <div>
                            <h4>Teléfono</h4>
                            <p>+52 555 123 4567</p>
                        </div>
                    </div>
                    <div class="item-contacto">
                        <i class="fa-solid fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>info@atasko.com</p>
                        </div>
                    </div>
                </div>

                <div class="sociales">
                    <h4>Síguenos:</h4>
                    <div class="iconos-sociales">
                        <a href="https://www.facebook.com/" target="_blank"><i class="fa-brands fa-facebook"></i></a>
                        <a href="https://www.instagram.com/" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://x.com/" target="_blank"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <div class="form-container">
                <form id="contact-form" class="formulario">
                    <div class="campo">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" placeholder="Ej. Juan Pérez" required>
                    </div>
                    
                    <div class="campo">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" placeholder="tucorreo@ejemplo.com" required>
                    </div>

                    <div class="campo">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" rows="5" placeholder="Escribe tu mensaje aquí..." required></textarea>
                    </div>

                    <button type="submit" class="boton-enviar">Enviar Mensaje</button>
                </form>
                <div id="form-message" class="form-status-mensaje"></div>
            </div>

        </div>
    </section>

    <?php require 'includes/footer.php'; ?>

       <script>
        document.getElementById('contact-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = event.target;
            const messageDiv = document.getElementById('form-message');
            
            const data = {
                nombre: form.querySelector('#nombre').value,
                correo: form.querySelector('#email').value,
                mensaje: form.querySelector('#mensaje').value
            };

            messageDiv.textContent = '';
            messageDiv.className = 'form-status-mensaje';

            fetch('api/contact.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(responseData => {
                messageDiv.textContent = responseData.message;
                if (responseData.status === 'success') {
                    messageDiv.classList.add('exito');
                    form.reset();
                } else {
                    messageDiv.classList.add('error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.textContent = 'Ocurrió un error inesperado.';
                messageDiv.classList.add('error');
            });
        });
    </script>