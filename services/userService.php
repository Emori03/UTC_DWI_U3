<?php
function getUsuarios() {
    global $cnnPDO;  // Usamos la conexión a la base de datos

    $query = $cnnPDO->prepare('SELECT id, nombre, email, role FROM usuarios');
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}
?>
