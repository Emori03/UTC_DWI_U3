<?php
session_start();
require 'conexion.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['session_key'])) {
    try {
        // Establecer el session_key a NULL en la base de datos para la sesión actual
        $updateQuery = $cnnPDO->prepare('UPDATE usuarios SET session_key = NULL WHERE id = :id AND session_key = :session_key');
        $updateQuery->bindParam(':id', $_SESSION['user_id']);
        $updateQuery->bindParam(':session_key', $_SESSION['session_key']);
        $updateQuery->execute();

        // Verificar si la actualización fue exitosa
        if ($updateQuery->rowCount() > 0) {
            // Destruir la sesión
            session_unset();
            session_destroy();
        } else {
            // Manejar el caso donde no se pudo actualizar el session_key
            echo "Error: No se pudo actualizar el session_key.";
        }
    } catch (PDOException $e) {
        echo "Error al cerrar la sesión: " . $e->getMessage();
    }
}

// Redirigir al usuario a la página de inicio de sesión
header("Location: index.php");
exit();
?>
