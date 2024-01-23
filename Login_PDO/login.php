<?php
session_start();

if (isset($_POST['usuario']) && isset($_POST['password'])) {
  // Verificación de credenciales en la base de datos y configuración de la sesión
  $usuario = strtolower($_POST['usuario']);
  $password = hash('sha512', $_POST['password']);

    try {
      //code...
    $host = "webserverdb.cjg5owi8xt5m.us-east-1.rds.amazonaws.com";
    $dbUsername = "admin";
    $dbPassword = "IJDgwMtFPeDIf2KtvVY6";
    $dbName = "webserverdb";
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $dbUsername, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $statement = $conn->prepare('SELECT * FROM Users WHERE UserId = :usuario AND Password = :password');
    $statement->execute(array(':usuario' => $usuario, ':password' => $password));
    $resultado = $statement->fetch();

    if ($resultado) {
        $_SESSION['usuario'] = $usuario;
        header("Location: contenido.php");
        exit; // Asegurarse de que la ejecución del script se detenga aquí
    } else {
      echo "<p style= 'color: red; font-size: 28px; text-align:center; margin-top: 4%; margin-bottom: 0%';>Usuario o contraseña incorrectos.</p>";
      echo "<p style= 'color: red; font-weight: bold; font-size: 25px; text-align:center; margin-top: 2%; margin-bottom: 0%';>¿Tienes cuenta? <a href='./registro.php'>Registrar</a></p>";
    }
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    };
  }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="Style/style.css" rel="stylesheet">
</head>

<body>

    <div id="contenedor">
        <div id="log" style="display:block;">
            <h2>Iniciar sesión</h2>
            <hr>
            <br>
            <form action="login.php" method="post">
                Usuario: <input type="text" name="usuario"><br>
                Contraseña: <input type="password" name="password"><br>
                <input type="submit" value="Iniciar sesión">
            </form>
            <br>
            <br>
            <hr>
            <br>
            <p>¿Nuevo usuario? <a href="./registro.php">Registrar</a>
        </div>
    </div>
</body>

</html>