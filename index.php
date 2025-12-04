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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const productGrid = document.getElementById('product-grid');
        const filterButtons = document.querySelectorAll('.btn-filtro');
        let allProducts = [];

        // --- FUNCIÓN PARA RENDERIZAR PRODUCTOS ---
        function renderProducts(products) {
            productGrid.innerHTML = ''; // Limpiar el grid
            if (products.length === 0) {
                productGrid.innerHTML = '<p>No se encontraron productos.</p>';
                return;
            }
            products.forEach(product => {
                const category = product.idcategoria === 1 ? 'juguetes' : 'alimento';
                const imageUrl = product.imagen ? `data:image/jpeg;base64,${product.imagen}` : `https://placehold.co/300x300/e8f5e9/2e7d32?text=${encodeURIComponent(product.nombre)}`;
                const productItem = document.createElement('div');
                productItem.className = 'producto-item';
                productItem.setAttribute('data-category', category);

                let adminButtons = '';
                if (USER_ROLE == 1) { // 1 es el rol de Admin
                    adminButtons = `<button class="btn-delete" data-id="${product.idproducto}">Eliminar</button>`;
                }

                productItem.innerHTML = `
                        <div class="img-container">
                            <img src="${imageUrl}" alt="${product.nombre}">
                        </div>
                        <div class="info-producto">
                            <h4>${product.nombre}</h4>
                            <p class="desc-corta">${product.descripcion || ''}</p>
                            <div class="precio-row">
                                <p class="precio">$${parseFloat(product.precio).toFixed(2)} MXN</p>
                                <button class="btn-add" data-id="${product.idproducto}"><i class="fa-solid fa-plus"></i></button>
                            </div>
                            <div class="admin-actions">
                                ${adminButtons}
                            </div>
                        </div>
                    `;
                productGrid.appendChild(productItem);
            });
        }

        // --- FUNCIÓN PARA OBTENER PRODUCTOS (API) ---
        function fetchProducts() {
            fetch('api/get_products.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        allProducts = data.data;
                        renderProducts(allProducts);
                    } else {
                        productGrid.innerHTML = `<p>${data.message}</p>`;
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los productos:', error);
                    productGrid.innerHTML = '<p>Ocurrió un error al cargar los productos.</p>';
                });
        }

        // --- LÓGICA DE FILTRADO ---
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('activo'));
                button.classList.add('activo');
                const filter = button.getAttribute('data-filter');
                if (filter === 'todos') {
                    renderProducts(allProducts);
                } else {
                    const filteredProducts = allProducts.filter(p => {
                        const category = p.idcategoria === 1 ? 'juguetes' : 'alimento';
                        return category === filter;
                    });
                    renderProducts(filteredProducts);
                }
            });
        });

        // --- DELEGACIÓN DE EVENTOS (AÑADIR Y ELIMINAR) ---
        productGrid.addEventListener('click', function(event) {
            const target = event.target;

            // Botón de añadir al carrito
            const addButton = target.closest('.btn-add');
            if (addButton) {
                const productId = addButton.dataset.id;
                addToCart(productId);
                return; // Detener para no procesar otros clics
            }

            // Botón de eliminar producto (Admin)
            const deleteButton = target.closest('.btn-delete');
            if (deleteButton) {
                const productId = deleteButton.dataset.id;
                deleteProduct(productId);
                return;
            }
        });

        function addToCart(productId) {
            fetch('api/cart.php?action=add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idproducto: productId,
                        cantidad: 1
                    })
                })
                .then(response => {
                    if (response.status === 403) { // No autenticado
                        window.location.href = 'login.php';
                        return Promise.reject('Not authenticated');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'success') {
                        alert(data.message);
                    } else if (data) {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    if (error !== 'Not authenticated') {
                        console.error('Error al añadir al carrito:', error);
                        alert('No se pudo añadir el producto. Intente de nuevo.');
                    }
                });
        }

        function deleteProduct(productId) {
            if (!confirm('¿Estás seguro de que quieres eliminar este producto? Esta acción no se puede deshacer.')) {
                return;
            }

            fetch('api/product.php?action=delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        idproducto: productId
                    })
                })
                .then(response => {
                    if (response.status === 403) {
                        alert('No tienes permiso para realizar esta acción.');
                        return Promise.reject('Permission denied');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'success') {
                        alert(data.message);
                        fetchProducts(); // Recargar la lista de productos
                    } else if (data) {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    if (error !== 'Permission denied') {
                        console.error('Error al eliminar el producto:', error);
                        alert('No se pudo eliminar el producto. Intente de nuevo.');
                    }
                });
        }

        // --- Carga inicial de productos ---
        fetchProducts();
    });
</script>