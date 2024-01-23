<?php

// Establecer el tiempo de expiración de la cookie
$expire = time() + 5; // 5 segundos

// Crear la cookie
setcookie('Oreo', 'dobleCrema', $expire);

// Redirigir a la misma página
header('Location: ' . $_SERVER['PHP_SELF']);
exit();

session_start();

if (isset($_SESSION['usuario'])) {
    header("Location: contenido.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
