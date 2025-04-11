<?php
// Iniciar la sesión
session_start();

// Obtener el ID del producto desde el cuerpo de la solicitud (AJAX)
$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['id'];

// Verificar si el carrito existe en la sesión
if (isset($_SESSION['carrito'])) {
    // Obtener el carrito de la sesión
    $carrito = $_SESSION['carrito'];

    // Buscar el índice del producto a eliminar
    foreach ($carrito as $index => $producto) {
        if ($producto['id'] == $productId) {
            // Eliminar el producto del carrito
            unset($carrito[$index]);
            // Reindexar el array para que las claves sean consecutivas
            $_SESSION['carrito'] = array_values($carrito);
            // Responder con éxito
            echo json_encode(['success' => true, 'message' => 'Producto eliminado correctamente.']);
            exit;
        }
    }

    // Si no se encontró el producto, devolver un error
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado en el carrito.']);
} else {
    // Si no existe el carrito en la sesión
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
}
?>
