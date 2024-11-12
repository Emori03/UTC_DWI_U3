<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo contenga la conexión PDO

if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
    $currentTime = time(); // Hora actual en segundos desde el Unix Epoch

    // Usamos FROM_UNIXTIME() para convertir la marca de tiempo Unix a formato de fecha y hora
    $stmt = $cnnPDO->prepare("UPDATE usuarios SET last_login = FROM_UNIXTIME(?) WHERE email = ?");
    $stmt->execute([$currentTime, $userEmail]);
}
?>
