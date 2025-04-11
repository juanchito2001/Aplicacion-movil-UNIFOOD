<?php
session_start();

// Vaciar el carrito en la sesión
$_SESSION['carrito'] = []; // Vaciar carrito

// Enviar respuesta de éxito
echo json_encode(['success' => true]);
?>
