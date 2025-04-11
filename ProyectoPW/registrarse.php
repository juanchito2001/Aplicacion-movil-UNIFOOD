<?php 
  if($_POST){
    include 'php/conexion.php';
    //capturar datos
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];

    //insertar un nuevo usuario
    $query = "INSERT INTO usuarios (id, nombre, telefono, correo, contrasenia) VALUES (NULL, '$nombre', '$telefono', '$correo', '$contrasenia')";

    //Verificar correos
    $verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$correo' ");
    if(mysqli_num_rows($verificar_correo) > 0){
      $mensaje = "Ya existe un usuario con este correo";
    }else{
      //ejecucion para guardar usuario
      $ejecutar  = mysqli_query($conexion, $query);  
      if($ejecutar){
        header("Location: index.php");
        exit;
      }
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
  <!--Registrarse-->
  <div class="container-form register ">
    <div class="information">
      <div class="info-childs">
      <img src="assets/ImagenPW/logo2.png" alt="Bienvenido" style="width: 100%; max-width: 150px; margin-bottom: 20px;">
        <h2>Bienvenidos a UNI FOOD</h2>
        <p>Para unirte a nuestra comunidad por favor Inicia Sesion con tus datos</p>
        <input type="button" value="Iniciar Sesion" id="redireccionar">
      </div>
    </div>
    <div class="form-information">
      <div class="form-information-childs">
        <h2>Crear una cuenta</h2>
        <div class="icons">
          <i class='bx bxl-google'></i>
          <i class='bx bxl-google-plus'></i>
          <i class='bx bxl-yahoo'></i>
        </div>
        <!--Mensaje de error-->
        <?php if(isset($mensaje)){?>
            <strong style="color:black"> <?php echo $mensaje;?> </strong>
        <?php } ?>
        <p>Usa tu Correo Institucional para registrarse</p>
        <!--Formulario-->
        <form class="form" method="post">
          <label >
            <i class='bx bx-user' ></i>
            <input type="text" placeholder="Nombre Completo" name="nombre" id="nombre" autocomplete="off" required>
          </label>
          <label >
            <i class='bx bx-phone'></i>
            <input type="text" placeholder="Telefono" name="telefono" id="telefono" autocomplete="off" required>
          </label>
          <label >
            <i class='bx bx-envelope'></i>
            <input type="email" placeholder="Correo Electronico" name="correo" id="correo" autocomplete="off" required>
          </label>
          <label >
            <i class='bx bx-lock-alt'></i>
            <input type="password" placeholder="Contraseña" name="contrasenia" id="contrasenia" autocomplete="off" required>
          </label>
          <input type="submit" value="Registrarse">
        </form>
      </div>
    </div>
  </div> 

  <script src="assets/script/registrar.js"></script>
</body>
</html>