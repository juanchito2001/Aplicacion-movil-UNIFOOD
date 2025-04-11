<?php
  if ($_POST) {
    include 'php/conexion.php';

    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];

    // Validar las credenciales en la base de datos
    $validar = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' and contrasenia='$contrasenia'");

    if (mysqli_num_rows($validar) > 0) {
      // Si las credenciales son correctas, iniciar la sesión
      session_start();
      $usuario = mysqli_fetch_assoc($validar);
      
      // Guardar los datos del usuario en la sesión
      $_SESSION['id_usuario'] = $usuario['id'];  // ID del usuario
      $_SESSION['usuario_nombre'] = $usuario['nombre'];  // Nombre del usuario (opcional)
      $_SESSION['usuario_correo'] = $usuario['correo'];

      // Redirigir al home.php
      header("location: home.php");
      exit;
    } else {
      // Si las credenciales son incorrectas, mostrar un mensaje de error
      $mensaje = "Correo o contraseña incorrecto";
    }
  }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/png" href="assets/ImagenPW/logo.png">
    <link rel="stylesheet" href="assets/estilos/index2.css">
    <title>Uni Food</title>
</head>
<body>
  <!--Iniciar Sesion-->
  <div class="container-form login">
    <div class="information">
      <div class="info-childs">
        <h2>!!Bienvenido nuevamente¡¡</h2>
        <p>Para unirte a nuestra comunidad por favor Registrate con tus datos</p>
        <input type="button" value="Registrate" id="redireccionar">
      </div>
    </div>
    <div class="form-information">
      <div class="form-information-childs">
        <h2>Iniciar Sesión</h2>
        <!--Mensaje de alerta-->
        <?php if (isset($mensaje)) { ?>
            <strong style="color: black;"> <?php echo $mensaje; ?> </strong>
        <?php } ?>
        <div class="icons">
          <i class='bx bxl-google'></i>
          <i class='bx bxl-google-plus'></i>
          <i class='bx bxl-yahoo'></i>
        </div>
        <img src="assets/ImagenPW/logo2.png" alt="Bienvenido" style="width: 100%; max-width: 150px; margin-bottom: 20px;">
        <p>Inicia Sesión con tu cuenta</p>
        <!--Formulario-->
        <form  class="form" method="post">
          <label >
            <i class='bx bx-envelope'></i>
            <input type="email" placeholder="Correo Electronico" name="correo" id="correo" autocomplete="off" required>
          </label>
          <label >
            <i class='bx bx-lock-alt'></i>
            <input type="password" placeholder="Contraseña" name="contrasenia" id="contrasenia" autocomplete="off" required>
          </label>
          <input name="btnIniciarSesion" type="submit" value="Iniciar Sesión" id="iniciarSesion2">
        </form>
      </div>
    </div>
  </div>

  <script src="assets/script/index.js"></script>
</body>
</html>
