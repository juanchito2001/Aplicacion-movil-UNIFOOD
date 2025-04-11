<?php
    session_start();
    include 'php/conexion.php';

    // Verificar si el formulario fue enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $comentario = $_POST['comentario'] ?? '';
        $id_plato = $_POST['id_plato'] ?? '';
        $calificacion = $_POST['calificacion'] ?? 0;
        $id_usuario = $_SESSION['id_usuario'];

        if (!empty($comentario) && !empty($id_plato) && $calificacion >= 1 && $calificacion <= 5) {
            // Insertar la reseña en la base de datos
            $query_resena = "INSERT INTO resenas (id_usuario, id_plato, comentario, fecha) VALUES ('$id_usuario', '$id_plato', '$comentario', NOW())";
            if (mysqli_query($conexion, $query_resena)) {
                $id_resena = mysqli_insert_id($conexion); // Obtener el ID de la reseña insertada

                // Insertar la calificación en la base de datos
                $query_calificacion = "INSERT INTO calificaciones (id_resena, calificacion) VALUES ('$id_resena', '$calificacion')";
                if (mysqli_query($conexion, $query_calificacion)) {
                    echo "<script>alert('Reseña y calificación creadas exitosamente');</script>";
                    header("Location: home.php");
                    exit();
                } else {
                    echo "<script>alert('Error al guardar la calificación: " . mysqli_error($conexion) . "');</script>";
                }
            } else {
                echo "<script>alert('Error al crear la reseña: " . mysqli_error($conexion) . "');</script>";
            }
        } else {
            echo "<script>alert('Por favor completa todos los campos correctamente');</script>";
        }
    }

    // Consulta para obtener la lista de platos
    $platos = mysqli_query($conexion, "SELECT id, nombre FROM platos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/estilos/style2.css">
    <title>Crear Reseña</title>
</head>
<body>
    <div class="container">
        <h1>Crear Reseña</h1>
        <form method="POST" action="crearResena.php">
            <div class="form-group">
                <label for="id_plato">Selecciona un plato:</label>
                <select id="id_plato" name="id_plato" required>
                    <option value="">-- Selecciona un plato --</option>
                    <?php while ($plato = mysqli_fetch_assoc($platos)): ?>
                        <option value="<?php echo $plato['id']; ?>"><?php echo htmlspecialchars($plato['nombre']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="comentario">Tu reseña:</label>
                <textarea id="comentario" name="comentario" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="calificacion">Calificación (1-5):</label>
                <input type="number" id="calificacion" name="calificacion" min="1" max="5" required>
            </div>
            <div class="form-buttons">
                <button type="submit" class="btn btn-success">Enviar Reseña</button>
                <a href="home.php" class="btn btn-secondary">Regresar a Inicio</a>
            </div>
        </form>
    </div>
</body>
</html>
