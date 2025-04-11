<?php
    session_start();
    include 'php/conexion.php';

    $usuarioId = $_SESSION['id_usuario'];
    $metodoPago = ''; 
    $idEstado = 1;
    $total = 0;

    $carrito = $_SESSION['carrito'] ?? [];

    $mensaje = '';

    // Función para obtener id producto por nombre
    function obtenerIdProductoPorNombre($conexion, $nombreProducto) {
        $query = "SELECT id_producto FROM productos WHERE nombre = ? LIMIT 1";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $nombreProducto);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        if ($resultado->num_rows > 0) {
            $producto = $resultado->fetch_assoc();
            return $producto['id_producto'];
        } else {
            return null;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['metodoPago'])) {
        $metodoPago = $_POST['metodoPago']; // Obtener el método de pago seleccionado

        if (!empty($carrito)) {
            foreach ($carrito as $item) {
                if (isset($item['precio'], $item['cantidad'])) {
                    $precioSinSimbolo = str_replace('$', '', $item['precio']); 
                    $precioUnitario = (float) str_replace(',', '', $precioSinSimbolo);
                    $subtotal = $precioUnitario * $item['cantidad'];
                    $total += $subtotal;
                } else {
                    echo "Error: Producto sin precio o cantidad.";
                    exit;
                }
            }
    
            $codigoPedido = uniqid(); 
            $fechaPedido = date('Y-m-d H:i:s');

            $sqlPedido = "INSERT INTO pedidos (codigo_pedido, id_usuario, fecha_pedido, metodo_pago, id_estado, total) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtPedido = $conexion->prepare($sqlPedido);
            $stmtPedido->bind_param("sissid", $codigoPedido, $usuarioId, $fechaPedido, $metodoPago, $idEstado, $total);

            if ($stmtPedido->execute()) {
                $idPedido = mysqli_insert_id($conexion);

                foreach ($carrito as $item) {
                    $precioSinSimbolo = str_replace('$', '', $item['precio']);
                    $precioUnitario = (float) str_replace(',', '', $precioSinSimbolo);
                    $subtotal = $precioUnitario * $item['cantidad'];

                    $idProducto = obtenerIdProductoPorNombre($conexion, $item['nombre']);

                    if (!$idProducto) {
                        echo "Error: El producto '{$item['nombre']}' no se encontró en la base de datos.";
                        exit;
                    }

                    $sqlDetalle = "INSERT INTO detalles_pedido (id_pedido, id_usuario, id_producto, cantidad, subtotal) VALUES (?, ?, ?, ?, ?)";
                    $stmtDetalle = $conexion->prepare($sqlDetalle);
                    $stmtDetalle->bind_param("iiidi", $idPedido, $usuarioId, $idProducto, $item['cantidad'], $subtotal);

                    if (!$stmtDetalle->execute()) {
                        echo "Error al insertar el detalle del pedido: " . mysqli_error($conexion);
                        exit;
                    }
                }
                $mensaje = "Pedido guardado correctamente!";
            } else {
                echo "Error al insertar el pedido: " . mysqli_error($conexion);
                exit;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Pedido</title>
    <link rel="stylesheet" href="assets/estilos/style2.css">
</head>
<body>
    <h1>Selecciona tu método de pago</h1>
    <form action="guardarPedido.php" method="POST">
        <label for="metodoPago">Método de Pago:</label>
        <select name="metodoPago" id="metodoPago" required>
            <option value="Nequi">Nequi</option>
            <option value="BanColombia">BanColombia</option>
            <option value="DaviPlata">DaviPlata</option>
            <option value="PayPal">PayPal</option>
            <option value="Tarjeta Crédito">Tarjeta Crédito</option>
            <option value="Tarjeta Débito">Tarjeta Débito</option>
        </select>
        <button type="submit">Confirmar y Guardar Pedido</button>
    </form>

    <?php if ($mensaje): ?>
        <h2><?php echo $mensaje; ?></h2>
    <?php endif; ?>

    <form action="home.php" method="POST">
        <button type="submit" name="cancelar" class="btn btn-cancel">Volver</button>
    </form>
</body>
</html>
