<?php require 'includes/header.php'; ?>

    <section class="hero hero-interno">
        <div class="hero-overlay"></div>
        <div class="contenedor contenido-hero">
            <h2>Nuestro Catálogo</h2>
            <p>Explora la variedad completa de productos para tu mascota.</p>
        </div>
    </section>

    <section class="seccion productos">
        <div class="contenedor">
            
            <div class="filtros-container">
                <button class="btn-filtro activo" data-filter="todos">Todos</button>
                <button class="btn-filtro" data-filter="perros">Perros</button>
                <button class="btn-filtro" data-filter="gatos">Gatos</button>
                <button class="btn-filtro" data-filter="accesorios">Accesorios</button>
                <button class="btn-filtro" data-filter="salud">Salud</button>
            </div>

            <div class="grid-productos" id="product-grid">
                <!-- Los productos se cargarán aquí dinámicamente -->
            </div>
        </div>
    </section>

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

            // --- AÑADIR AL CARRITO (DELEGACIÓN DE EVENTOS) ---
            productGrid.addEventListener('click', function(event) {
                const addButton = event.target.closest('.btn-add');
                if (addButton) {
                    const productId = addButton.dataset.id;
                    addToCart(productId);
                }
            });

            function addToCart(productId) {
                fetch('api/cart.php?action=add', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ idproducto: productId, cantidad: 1 })
                })
                .then(response => {
                    if (response.status === 403) { // No autenticado
                        window.location.href = 'login.php';
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'success') {
                        alert(data.message); // O una notificación más elegante
                    } else if (data) {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al añadir al carrito:', error);
                    alert('No se pudo añadir el producto. Intente de nuevo.');
                });
            }

            // --- Carga inicial de productos ---
            fetchProducts();
        });
    </script>
    <?php require 'includes/footer.php'; ?>