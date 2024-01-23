<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Contenido</title>
<link href="Style/style2.css" rel="stylesheet">
</head>
<body>
    <div class="image-container">
        <h1>Welcome, <?php echo $_SESSION['usuario']; ?>!</h1>
        <h5>Te dejo unas imágenes generadas por IA para tu disfrute. Estas personas no existen realmente:</h5>
        <div class="image-row">
            <div class="image-column">
                <img class="image" src="Img/Cara1.png" alt="Imagen 1">
            </div>
            <div class="image-column">
                <img class="image" src="Img/Cara2.png" alt="Imagen 2">
            </div>
            <div class="image-column">
                <img class="image" src="Img/Cara3.png" alt="Imagen 3">
            </div>
        </div>
        <div class="image-row">
            <div class="image-column">
                <img class="image" src="Img/Cara4.png" alt="Imagen 4">
            </div>
            <div class="image-column">
                <img class="image" src="Img/Cara5.png" alt="Imagen 5">
            </div>
            <div class="image-column">
                <img class="image" src="Img/Cara6.png" alt="Imagen 6">
            </div>
        </div>
        <h5>Y esta es la interpretación de la IA de un bosque con diferentes estilos artisticos:</h5>
        <div class="image-row">
            <div class="image-column">
                <img class="image" src="Img/bosque1.png" alt="Imagen 7">
            </div>
            <div class="image-column">
                <img class="image" src="Img/bosque2.png" alt="Imagen 8">
            </div>
            <div class="image-column">
                <img class="image" src="Img/bosque3.png" alt="Imagen 9">
            </div>
        </div>
        <div class="image-row">
            <div class="image-column">
                <img class="image" src="Img/bosque4.png" alt="Imagen 10">
            </div>
            <div class="image-column">
                <img class="image" src="Img/bosque5.png" alt="Imagen 11">
            </div>
            <div class="image-column">
                <img class="image" src="Img/bosque6.png" alt="Imagen 12">
            </div>
        </div>
        <h2><a href="logout.php">Cerrar sesión</a></h2>
    </div>
</body>
</html>
