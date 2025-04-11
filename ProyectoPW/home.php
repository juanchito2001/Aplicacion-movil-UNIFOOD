<?php 
    session_start();
    include 'php/conexion.php';
    $carrito = $_SESSION['carrito'] ?? [];
    $usuarioId = $_SESSION['id_usuario'];  // ID del usuario
    $usuarioNombre = $_SESSION['usuario_nombre'];  // Nombre del usuario (opcional)
    $usuarioCorreo = $_SESSION['usuario_correo'];

    $limit = 8;
    //Seleccionamos todos los platos y bebidas
    $listaPlatos = mysqli_query($conexion, "SELECT * FROM platos LIMIT $limit");
    $listaBebidas = mysqli_query($conexion, "SELECT * FROM bebidas LIMIT $limit");

    // Consulta para obtener reseñas con información del usuario y del plato
    $consultaResenas = mysqli_query($conexion, "
        SELECT r.comentario, r.fecha, u.nombre AS usuario, p.nombre AS plato
        FROM resenas r
        JOIN usuarios u ON r.id_usuario = u.id
        JOIN platos p ON r.id_plato = p.id
        ORDER BY r.fecha DESC
    ");

    // Consulta para obtener los platos mejor puntuados junto con información de la reseña y usuario
    $consultaDestacados = mysqli_query($conexion, "
        SELECT p.nombre AS plato, p.imagen, r.comentario, u.nombre AS usuario, c.calificacion
        FROM calificaciones c
        JOIN resenas r ON c.id_resena = r.id
        JOIN usuarios u ON r.id_usuario = u.id
        JOIN platos p ON r.id_plato = p.id
        ORDER BY c.calificacion DESC, r.fecha DESC
        LIMIT 3
    ");

    // Verificar si la consulta se ejecutó correctamente
    if (!$consultaDestacados) {
        die("Error en la consulta de destacados: " . mysqli_error($conexion));
    }

    // Vaciar el carrito y redirigir si se presiona "Cancelar"
    if (isset($_POST['cancelar'])) {
        unset($_SESSION['carrito']);
        header("Location: home.php");
        exit;
    }



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/png" href="assets/ImagenPW/logo.png">
    <link rel="stylesheet" href="assets/estilos/style2.css">
    <title>Uni Food</title>
</head>
<body>
    
    <header id="principal" class="header">
        <!--Menu principal-->
        <div class="menu container">
            <a href="#principal" class="logo">
                <img src="assets/ImagenPW/logo2.png" alt="Logo" style="width: 100px; height: auto;">
            </a>         
            <input type="checkbox" id="menu" />
            <nav class="navbar">
                <ul>
                    <li> <a href="#principal">Inicio</a></li>
                    <!--
                        <li> <a href="#Servicios">Servicios</a></li>
                    -->
                    <li> <a href="#seccion-productos">Productos</a></li>
                    <li> <a href="#seccion-destacados">Mejores Platos</a></li>
                    <li> <a href="#seccionTestimonios">Reseñas</a></li>
                    <li> <a href="#seccion-contactos">Contactos</a></li>
                </ul>
            </nav>
            <div>
                <ul class="submenus">
                    <li class="submenu2">
                        <div id="icono-perfil">
                            <i class='bx bx-user'></i>
                        </div>
                        <div id="informacion-usuario">
                            <ul>
                                <li><a href="#">Perfil</a></li>
                                <li><a href="#">Historial de compras</a></li>
                                <li><a href="#">Notificaciones</a></li>
                                <!--mas adelante-->
                                <li><a href="#">Estado de compra</a></li>
                                <li><a href="#">Ajustes</a></li>
                                <li><a href="php/CerrarSesion.php">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="submenu">
                        <div id="icono-Carrito">
                            <i class='bx bxs-cart-alt bx-tada bx-flip-horizontal' ></i>
                        </div>
                        <div id="carrito">
                            <table id="lista-carrito">
                                <thead>
                                    <tr>
                                        <th>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <a href="#" id="vaciar-carrito" class="btn-1">Vaciar carrito</a>
                            <a href="comprar.php" id="Comprar" class="btn-1">Comprar</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!--Contenido del encabezado-->
        <div id="Servicios" class="header-content container">
            <div class="header-txt">
                <span>Bienvenidos a nuestro restaurante</span>
                <h1>¡Sabores que te hacen sentir en casa!</h1>
                <p>
                    En nuestro restaurante, te ofrecemos una experiencia gastronómica única con sabores caseros y saludables. 
                    Cada plato está preparado con ingredientes frescos y seleccionados, pensado para satisfacer los paladares más exigentes. 
                    Disfruta de nuestras ofertas especiales del día, donde podrás encontrar desde platos tradicionales hasta opciones innovadoras, 
                    todo a precios accesibles para estudiantes y personal de la universidad. ¡Ven a visitarnos y déjate sorprender por el sabor!
                </p>
                
                <div class="butons">
                    <a href="#" class="btn-1">informacion</a>
                    <a href="#" class="btn-1">leer mas</a>
                </div>
            </div>
            <div class="header-img">
                <img src="assets/images/fotoprincipal.png" alt="imagen bolsa">
            </div>
        </div>
    </header>


    <div id="containerMejoresPlatos">
        <h2 class="titulo-centrado">Mejores Platos</h2>
    </div>
    <section id="seccion-destacados" class="oferts container">
    <br>
    <?php
        // Comprobar si hay platos destacados
        if (mysqli_num_rows($consultaDestacados) > 0) {
            // Iterar sobre cada plato destacado
            while ($destacado = mysqli_fetch_assoc($consultaDestacados)) {
                echo '<div class="ofert-1 b1">';
                echo '    <div class="ofert-txt">';
                echo '        <h3>' . htmlspecialchars($destacado['plato']) . '</h3>';
                echo '        <p>Calificación: ' . htmlspecialchars($destacado['calificacion']) . ' estrellas</p>';
                echo '        <p>Comentario: "' . htmlspecialchars($destacado['comentario']) . '"</p>';
                echo '        <small>Usuario: ' . htmlspecialchars($destacado['usuario']) . '</small>';
                echo '        <a href="#">Leer más</a>';
                echo '    </div>';
                echo '    <div class="ofert-img">';
                echo '        <img src="' . htmlspecialchars($destacado['imagen']) . '" alt="' . htmlspecialchars($destacado['plato']) . '">';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo '<p>No hay platos destacados disponibles en este momento.</p>';
        }
    ?>
    <br>
    </section>

    <div id="containerMejoresPlatos">
        <h2 class="titulo-centrado">Mejores Platos</h2>
    </div>
    <main id="seccion-productos" class="products container">
        <div class="box-container" id="lista-1">
            <!--Platos-->
            <?php
                // Comprobamos si hay platos
                if (mysqli_num_rows($listaPlatos) > 0) {
                    // Iteramos sobre cada plato
                    while ($plato = mysqli_fetch_assoc($listaPlatos)) {
                        echo '<div class="box">';
                        echo '<img src="'. $plato['imagen'] . '" alt="' . htmlspecialchars($plato['nombre']) . '">';
                        echo '<div class="product-txt">';
                        echo '<h3>' . htmlspecialchars($plato['nombre']) . '</h3>';
                        echo '<p>' . htmlspecialchars($plato['descripcion']) . '</p>';
                        echo '<p class="precio">$' . htmlspecialchars($plato['precio']) . '</p>';
                        echo '<a href="#" class="agregar-carrito btn-3" data-id="' . $plato['id'] . '">Agregar al carrito</a>';
                        echo '</div>';    
                        echo '</div>';
                    }
                } else {
                    echo '<p>No hay platos disponibles.</p>';
                }
            ?>
        </div>
    </main>

    <main id="seccion-bebidas" class="products container">
        <h2>Bebidas</h2>
        <div class="box-container" id="lista-2">
            <!--Bebidas-->
            <?php
                // Comprobamos si hay bebidas
                if (mysqli_num_rows($listaBebidas) > 0) {
                    // Iteramos sobre cada bebida
                    while ($bebida = mysqli_fetch_assoc($listaBebidas)) {
                        if($bebida['id_tipo_bebida'] === "3"){
                            $imagenBebida = "images/cup.png";
                        }else{
                            $imagenBebida = "images/glass-bottle.png";
                        }
                        echo '<div class="box">';
                        echo '<img src="assets/'.$imagenBebida.'" alt="' . htmlspecialchars($bebida['nombre']) . '">';
                        echo '<div class="product-txt">';
                        echo '<h3>' . htmlspecialchars($bebida['nombre']) . '</h3>';
                        echo '<p class="precio">$' . htmlspecialchars($bebida['precio']) . '</p>';
                        echo '<a href="#" class="agregar-carrito btn-3" data-id="' . $bebida['id'] . '">Agregar al carrito</a>';
                        echo '</div>';    
                        echo '</div>';
                    }
                } else {
                    echo '<p>No hay bebidas disponibles.</p>';
                }
            ?>
        </div>
    </main>
    

    <section id="seccionTestimonios" class="testimonial container">
    <span>Testimonios</span>
    <h2>Lo que dicen nuestros clientes</h2>
    <div class="testimonial-content">
        <?php
            // Consulta para obtener reseñas con calificaciones
            $queryResenas = "
                SELECT r.comentario, r.fecha, u.nombre AS usuario, p.nombre AS plato, c.calificacion
                FROM resenas r
                LEFT JOIN usuarios u ON r.id_usuario = u.id
                LEFT JOIN platos p ON r.id_plato = p.id
                LEFT JOIN calificaciones c ON r.id = c.id_resena
                ORDER BY r.fecha DESC
            ";
            $consultaResenas = mysqli_query($conexion, $queryResenas);

            // Comprobar si hay reseñas
            if (mysqli_num_rows($consultaResenas) > 0) {
                // Mostrar cada reseña obtenida
                while ($resena = mysqli_fetch_assoc($consultaResenas)) {
                    echo '<div class="testimonial-1">';
                    echo '<p>"' . htmlspecialchars($resena['comentario']) . '"</p>';
                    
                    // Mostrar estrellas según la calificación
                    $calificacion = (int)$resena['calificacion'];
                    echo '<div class="calificacion">';
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $calificacion) {
                            echo '<img src="assets/images/star-filled.png" alt="Estrella llena" class="estrella">';
                        } else {
                            echo '<img src="assets/images/star-empty.png" alt="Estrella vacía" class="estrella">';
                        }
                    }
                    echo '</div>';
                    
                    echo '<h4>' . htmlspecialchars($resena['usuario']) . '</h4>';
                    echo '<small>Plato: ' . htmlspecialchars($resena['plato']) . ' | Fecha: ' . htmlspecialchars($resena['fecha']) . '</small>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay reseñas disponibles en este momento.</p>';
            }
        ?>
    </div>
    <!-- Botón para crear una reseña -->
    <div class="crear-resena">
        <a href="crearResena.php" class="btn-1">Quiero hacer una reseña</a>
    </div>
    </section>


    
    <footer id="seccion-contactos" class="footer">
        <div class="footer-content container">
            <!-- Enlaces importantes -->
            <div class="link">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Menú</a></li>
                    <li><a href="#">Testimonios</a></li>
                    <li><a href="#">Contáctanos</a></li>
                </ul>
            </div>
    
            <!-- Redes sociales -->
            <div class="link">
                <h3>Redes Sociales</h3>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Twitter</a></li>
                    <li><a href="#">YouTube</a></li>
                </ul>
            </div>
    
            <!-- Información de contacto -->
            <div class="link">
                <h3>Contáctanos</h3>
                <ul>
                    <li><a href="tel:+573001234567">Teléfono: +57 300 123 4567</a></li>
                    <li><a href="Palacios:info@restaurante.com">Correo: info@restaurante.com</a></li>
                    <li><a href="#">#18-445 a, Cra. 13 #18571, Santander de Quilichao, Cauca</a></li>
                    <li><a href="#">Horario: Lunes - Sábado, 10:00 AM - 10:00 PM</a></li>
                </ul>
            </div>
    
            <!-- Políticas y términos -->
            <div class="link">
                <h3>Políticas</h3>
                <ul>
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Términos y condiciones</a></li>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Devoluciones y reembolsos</a></li>
                </ul>
            </div>
        </div>
        <!-- Derechos reservados -->
        <div class="footer-bottom">
        </div>
    </footer>
    
    <script src="assets/script/script.js"></script>
    </body>
    </html>
    