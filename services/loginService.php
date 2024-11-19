<?php
function login($email, $contrasena) {
    global $cnnPDO;  // Usamos la conexión a la base de datos

    $query = $cnnPDO->prepare('SELECT * FROM usuarios WHERE email = :email');
    $query->bindParam(':email', $email);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contrasena, $user['contrasena'])) {
        return [
            'status' => 'success',
            'user_id' => $user['id'],
            'nombre' => $user['nombre'],
            'email' => $user['email'],
            'role' => $user['role']
        ];
    } else {
        return ['status' => 'error', 'message' => 'Credenciales incorrectas'];
    }
}
?>
