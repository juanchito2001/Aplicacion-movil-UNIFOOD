<?php
session_start();

// Intentar capturar los errores con un bloque try-catch
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos JSON recibidos
        $carrito = json_decode(file_get_contents('php://input'), true);

        // Verificar que se recibieron datos válidos
        if (empty($carrito)) {
            throw new Exception('No se recibieron datos válidos del carrito.');
        }

        // Inicializar o recuperar el carrito de la sesión
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Procesar el carrito
        foreach ($carrito as $item) {
            // Verificar que cada item tiene las claves necesarias
            if (!isset($item['id'], $item['nombre'], $item['precio'], $item['cantidad'])) {
                throw new Exception('Datos incompletos en el carrito.');
            }

            // Aquí no convertimos el precio a número, lo dejamos tal cual
            // El precio se mantendrá con el símbolo $ como se envía desde el frontend

            // Verificar si el producto ya existe en el carrito y actualizar la cantidad
            $productoExistente = false;
            foreach ($_SESSION['carrito'] as &$carritoItem) {
                if ($carritoItem['id'] == $item['id']) {
                    $carritoItem['cantidad'] += $item['cantidad']; // Aumentar la cantidad si ya existe
                    $productoExistente = true;
                    break;
                }
            }

            // Si el producto no existe, agregarlo al carrito
            if (!$productoExistente) {
                $_SESSION['carrito'][] = $item;
            }
        }

        // Devolver respuesta exitosa
        echo json_encode(['success' => true]);

    } else {
        throw new Exception('Método no permitido');
    }
} catch (Exception $e) {
    // Capturar errores y devolverlos como respuesta JSON
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
