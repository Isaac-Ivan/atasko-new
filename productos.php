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
                <button class="btn-filtro" data-filter="1">Perros</button>
                <button class="btn-filtro" data-filter="2">Gatos</button>
                <button class="btn-filtro" data-filter="3">Accesorios</button>
                <button class="btn-filtro" data-filter="4">Salud</button>
            </div>

            <div id="CountProducts">

            </div>

            <div class="grid-productos" id="product-grid">
                
                <!-- Imprime lista de productos-->
            </div>
        </div>
    </section>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const productGrid = document.getElementById('product-grid');
            const filterButtons = document.querySelectorAll('.btn-filtro');
            let allProducts = [];
            let contadorProductos = 0;

            // --- FUNCIÓN PARA RENDERIZAR PRODUCTOS ---
            function renderProducts(products) {
                contadorProductos = 0;
                productGrid.innerHTML = ''; 
                if (products.length === 0) {
                    productGrid.innerHTML = '<p>No se encontraron productos.</p>';
                    return;
                }
                products.forEach(product => {
                    contadorProductos++;
                    const category = product.idcategoria ;
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

                document.getElementById('CountProducts').innerHTML = `<h4 class="info-producto">Productos encontrados: ${contadorProductos}</h4>`;
                 console.log(contadorProductos)
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
                        const filteredProducts = allProducts.filter(p => p.idcategoria == filter);
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
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ idproducto: productId, cantidad: 1 })
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
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ idproducto: productId })
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
    <?php require 'includes/footer.php'; ?>