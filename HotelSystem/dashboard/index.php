<?php
// Iniciar sesión (opcional si usarás sesiones)
//session_start();

// Redirigir a principal.php
header("Location: views/modulos/principal.php");
exit; // Importante para evitar que el script siga ejecutándose
?>