<?php
session_start(); // Inicia la sesión

// Destruir todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de login
header('Location: ../../login.php');
exit();
?>