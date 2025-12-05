<?php require 'includes/header.php'; ?>

<section class="hero">
    <div class="hero-overlay"></div>
    <div class="contenedor contenido-hero">
        <h2>Consiente a tu mejor amigo</h2>
        <p>Descubre productos premium seleccionados para la felicidad y salud de tus mascotas.</p>
        <div class="botones-hero">
            <a href="productos.php" class="boton-principal">Ver Catálogo</a>
            <a href="#fav" class="boton-secundario">Ofertas</a>
        </div>
    </div>
</section>

<section class="seccion categorias">
    <div class="contenedor">
        <div class="titulo-seccion">
            <h3>Explora por Categoría</h3>
            <p>Todo lo que necesitas en un solo lugar</p>
        </div>

        <div class="grid-categorias">
            <div class="categoria-item">
                <div class="icono-cat"><i class="fa-solid fa-bowl-food"></i></div>
                <h4>Alimentos</h4>
            </div>
            <div class="categoria-item">
                <div class="icono-cat"><i class="fa-solid fa-baseball"></i></div>
                <h4>Juguetes</h4>
            </div>
            <div class="categoria-item">
                <div class="icono-cat"><i class="fa-solid fa-shirt"></i></div>
                <h4>Accesorios</h4>
            </div>
            <div class="categoria-item">
                <div class="icono-cat"><i class="fa-solid fa-heart-pulse"></i></div>
                <h4>Salud</h4>
            </div>
        </div>
    </div>
</section>

<section class="seccion resenas-bg">
    <div class="contenedor">
        <div class="titulo-seccion">
            <h3>Clientes Felices</h3>
            <p>Ellos y sus mascotas aman Atasko</p>
        </div>

        <div class="slider-resenas">

            <div class="resena-card">
                <div class="header-resena">
                    <img src="https://placehold.co/60x60/2E7D32/ffffff?text=Juan" alt="Cliente">
                    <div>
                        <h4>Juan y 'Rocco'</h4>
                        <div class="estrellas">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="texto-resena">"¡Increíble servicio! El pedido llegó en menos de 24 horas y a Rocco le encantó su cama nueva. Definitivamente volveré a comprar."</p>
                <i class="fa-solid fa-quote-right icono-quote"></i>
            </div>

            <div class="resena-card">
                <div class="header-resena">
                    <img src="https://placehold.co/60x60/4CAF50/ffffff?text=Ana" alt="Cliente">
                    <div>
                        <h4>Ana y 'Mishi'</h4>
                        <div class="estrellas">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-regular fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="texto-resena">"La calidad del rascador es superior a lo que esperaba por el precio. Mishi no deja de jugar con él. Muy recomendados."</p>
                <i class="fa-solid fa-quote-right icono-quote"></i>
            </div>

            <div class="resena-card">
                <div class="header-resena">
                    <img src="https://placehold.co/60x60/81C784/ffffff?text=Luis" alt="Cliente">
                    <div>
                        <h4>Luis y 'Max'</h4>
                        <div class="estrellas">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="texto-resena">"Me encanta que tengan productos naturales. Compré los premios de hígado y Max se vuelve loco cada vez que abro la bolsa."</p>
                <i class="fa-solid fa-quote-right icono-quote"></i>
            </div>

            <div class="resena-card">
                <div class="header-resena">
                    <img src="https://placehold.co/60x60/2E7D32/ffffff?text=Carla" alt="Cliente">
                    <div>
                        <h4>Carla y 'Luna'</h4>
                        <div class="estrellas">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="texto-resena">"La atención al cliente es excelente. Tuve una duda con la talla del collar y me ayudaron al instante por WhatsApp."</p>
                <i class="fa-solid fa-quote-right icono-quote"></i>
            </div>

        </div>

        <p style="text-align: center; margin-top: 10px; color: #999; font-size: 0.8rem;">
            <i class="fa-solid fa-arrows-left-right"></i> Desliza para ver más
        </p>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
