<?php
session_start();

// Vaciar el carrito si el usuario presiona el botón "Volver al Menú"
if (isset($_POST['volver_menu'])) {
    unset($_SESSION['carrito']);
    header("Location: home.php");
    exit;
}

$total = $_POST['total'] ?? 0;
$mensaje_pago = '';
$error_pago = '';

// Verificar si el total es válido
if ($total <= 0) {
    echo "Error: El total de la compra no es válido.";
    exit;
}

// Procesar la selección de pago
if (isset($_POST['metodo_pago'])) {
    $metodo_pago = $_POST['metodo_pago'];
    $mensaje_pago = match ($metodo_pago) {
        'nequi' => "Pago exitoso con Nequi. ¡Gracias por tu compra!",
        'tarjeta' => "Pago exitoso con tarjeta de crédito. ¡Gracias por tu compra!",
        default => "Ocurrió un error, por favor elige un método de pago válido.",
    };
} else {
    $error_pago = "Por favor, elige un método de pago.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulación de Pago - Uni Food</title>
    <link rel="stylesheet" href="assets/estilos/style2.css">
    <style>
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            text-align: center;
        }
        .total-amount {
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .payment-methods {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .payment-methods label {
            padding: 10px 20px;
            background-color: #f0f0f0;
            border-radius: 5px;
            cursor: pointer;
        }
        .payment-methods input[type="radio"] {
            display: none;
        }
        .payment-methods input[type="radio"]:checked + label {
            background-color: #4CAF50;
            color: white;
        }
        .btn-finalizar, .btn-volver {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            color: white;
        }
        .btn-finalizar {
            background-color: #4CAF50;
            margin-top: 20px;
        }
        .btn-volver {
            background-color: #f44336;
            margin-top: 20px;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Simulación de Pago</h1>

        <!-- Mostrar el total de la compra -->
        <div class="total-amount">
            <h2>Total a Pagar: $<?php echo number_format($total, 3, '.', ','); ?></h2>
        </div>

        <!-- Mensaje de error si no se ha elegido un método de pago -->
        <?php if ($error_pago): ?>
            <p class="error"><?php echo $error_pago; ?></p>
        <?php endif; ?>

        <!-- Mensaje de éxito después del pago -->
        <?php if ($mensaje_pago): ?>
            <p><?php echo $mensaje_pago; ?></p>
            <!-- Botón para volver al menú después del pago -->
            <form action="pago.php" method="POST">
                <input type="hidden" name="volver_menu" value="true">
                <button type="submit" class="btn-volver" style="background-color: #4CAF50;">Volver al Menú</button>
            </form>
        <?php else: ?>
            <!-- Elegir método de pago -->
            <form action="pago.php" method="POST">
                <input type="hidden" name="total" value="<?php echo $total; ?>">

                <div class="payment-methods">
                    <div>
                        <input type="radio" id="nequi" name="metodo_pago" value="nequi">
                        <label for="nequi">Nequi</label>
                    </div>
                    <div>
                        <input type="radio" id="tarjeta" name="metodo_pago" value="tarjeta">
                        <label for="tarjeta">Tarjeta de Crédito</label>
                    </div>
                </div>

                <button type="submit" class="btn-finalizar">Realizar Pago</button>
            </form>

            <!-- Botón para volver al menú antes de realizar el pago -->
            <form action="pago.php" method="POST">
                <input type="hidden" name="volver_menu" value="true">
                <button type="submit" class="btn-volver">Volver al Menú</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
