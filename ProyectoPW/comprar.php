<?php
    session_start();
    include 'php/conexion.php';

    $usuarioId = $_SESSION['id_usuario']; // Supongo que tienes el ID del usuario almacenado en la sesión 
    $total = 0;
    
    // Verificar si el carrito está vacío o no
    $carrito = $_SESSION['carrito'] ?? [];

    if (!empty($carrito)) {
        foreach ($carrito as $item) {
            if (isset($item['precio'], $item['cantidad'])) {
                $precioSinSimbolo = str_replace('$', '', $item['precio']); 
                $precioUnitario = (float) str_replace(',', '', $precioSinSimbolo);
                // Calcular el subtotal 
                $subtotal = $precioUnitario * $item['cantidad']; 
                $total += $subtotal; // Sumar el subtotal de cada producto al total
            } else {
                echo "Error: Producto sin precio o cantidad.";
                exit;
            }
        }
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
    <title>Carrito de Compras - Uni Food</title>
    <link rel="stylesheet" href="assets/estilos/style2.css">
    <link rel="stylesheet" href="assets/estilos/estilosCompras.css">
</head>
<body>
    <div class="container">
        <h1>Carrito de Compras</h1>
        
        <?php if (!empty($carrito)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                            <td><?php echo htmlspecialchars($item['precio']); ?></td>
                            <td>
                                <?php 
                                    // Eliminar símbolos y comas solo para el cálculo
                                    $precioSinSimbolo = str_replace('$', '', $item['precio']); 
                                    $precioUnitario = (float) str_replace(',', '', $precioSinSimbolo);
                                    $subtotal = $precioUnitario * $item['cantidad'];
                                    // Mostrar con el formato adecuado y mantener el formato original de $88.000
                                    echo '$' . number_format($subtotal, 2, '.', ''); // Punto como separador de miles
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-container">
                <h2>Total a Pagar: <?php echo '$' . number_format($total, 2, '.', ','); ?></h2> <!-- Mostrar con punto como separador de miles -->
            </div>

            <div class="button-container">
                <form action="guardarPedido.php" method="POST">
                    <button type="submit" class="btn btn-confirm">Comprar</button>
                </form>
                <form action="comprar.php" method="POST">
                    <a href="#"><button type="submit" name="cancelar" class="btn btn-cancel">Cancelar</button></a>
                </form>
            </div>

        <?php else: ?>
            <p>Tu carrito está vacío. <a href="home.php">Regresar al menú</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
