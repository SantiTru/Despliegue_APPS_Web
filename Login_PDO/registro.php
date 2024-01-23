<?php
session_start();
$errores = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si todas las variables POST requeridas están definidas y no están vacías
    if (
        isset($_POST['nombre']) && isset($_POST['usuario']) &&
        isset($_POST['password']) && isset($_POST['password2']) &&
        !empty($_POST['nombre']) && !empty($_POST['usuario']) &&
        !empty($_POST['password']) && !empty($_POST['password2'])
    ) {
        $nombre = $_POST['nombre'];
        $usuario = strtolower($_POST['usuario']);
        $password = hash('sha512', $_POST['password']);
        $password2 = hash('sha512', $_POST['password2']);

        if ($password == $password2) {
            // Conexión a la base de datos y registro del usuario
            try {
                $host = "webserverdb.cjg5owi8xt5m.us-east-1.rds.amazonaws.com";
                $dbUsername = "admin";
                $dbPassword = "IJDgwMtFPeDIf2KtvVY6";
                $dbName = "webserverdb";
                $conn = new PDO("mysql:host=$host;dbname=$dbName", $dbUsername, $dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $statement = $conn->prepare('SELECT * FROM Users WHERE UserId = :usuario LIMIT 1');
                $statement->execute(array(':usuario' => $usuario));
                $resultado = $statement->fetch();

                if ($resultado) {
                    echo "<p style= 'color: red; font-size: 28px; text-align:center; margin-top: 4%; margin-bottom: 0%';>El usuario ya existe. Por favor, elige otro nombre de usuario.</p>";
                } else {
                    // Registro del usuario en la base de datos
                    $statement = $conn->prepare('INSERT INTO Users (Nombre, UserId, Password) VALUES (:nombre, :usuario, :password)');
                    $statement->execute(array(
                        ':nombre' => $nombre,
                        ':usuario' => $usuario,
                        ':password' => $password
                    ));

                    header("Location: login.php");
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "<p style= 'color: red; font-size: 28px; text-align:center; margin-top: 4%; margin-bottom: 0%';>Fallo en el registro. Las contraseñas no coinciden.</p>";
        }
    } else {
        // Manejo de errores en caso de datos faltantes
        echo "<p style= 'color: red; font-size: 28px; text-align:center; margin-top: 4%; margin-bottom: 0%';>Por favor, rellena todos los datos correctamente.</p>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registro de Usuario</title>
    <link href="Style/style.css" rel="stylesheet">
</head>

<body>
    <div id="registro" style="display: block;">
        <h2>Registro de Usuario</h2>
        <hr>
        <br>
        <?php
        // Mostrar errores solo si hay algún error
        if (!empty($errores)) {
            echo "<div style='text-align: center; margin-top: 3%'>";
            echo $errores;
            echo "<h4>" . "<a href='registro.php'>Volver al formulario de registro</a>" . "</h4>";
            echo "</div>";
        }
        ?>
        <form action="registro.php" method="post">
            Nombre completo: <input type="text" name="nombre"><br>
            Usuario: <input type="text" name="usuario"><br>
            Contraseña: <input type="password" name="password"><br>
            <input type="password" name="password2" placeholder="Repite Password">
            <input type="submit" value="Registrar">
            <br>
            <br>
            <hr>
            <br>
            <p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
        </form>
    </div>
</body>

</html>