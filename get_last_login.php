<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo contenga la conexión PDO

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
    $stmt = $cnnPDO->prepare("SELECT last_login FROM usuarios WHERE email = ?");
    $stmt->execute([$userEmail]);
    $lastLogin = $stmt->fetchColumn();

    if ($lastLogin) {
        // Convertir la cadena de fecha y hora a una marca de tiempo Unix
        $lastLoginTimestamp = strtotime($lastLogin);
        // Formatear la marca de tiempo Unix
        echo date('d-m-Y H:i:s', $lastLoginTimestamp);
    } else {
        echo "Nunca";
    }
}
?>
