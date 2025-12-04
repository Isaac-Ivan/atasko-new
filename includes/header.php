<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atasko - Tienda para Mascotas</title>
    <script>
        const USER_ROLE = <?php echo isset($_SESSION['user_role']) ? json_encode($_SESSION['user_role']) : '0'; ?>;
    </script>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="encabezado">
        <div class="contenedor contenido-header">
            <div class="logo">
                <h1>Atasko<span class="punto">.</span></h1>
            </div>
            
            <nav class="navegacion">
                <ul>
                    <li><a href="index.php" >Inicio</a></li>
                    <li><a href="productos.php">Productos</a></li>
                    <li><a href="acerca.php">Acerca de</a></li>
                    <li><a href="contacto.php">Contacto</a></li>
                </ul>
            </nav>

            <div class="iconos-nav">
                <a href="login.php" class="btn-icono"><i class="fa-solid fa-user"></i></a>
                <a href="carrito.php" class="btn-icono"><i class="fa-solid fa-cart-shopping"></i></a>
            </div>
        </div>
    </header>
