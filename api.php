<?php
header('Content-Type: application/json');
require 'conexion.php';
require 'services/loginService.php';  // Llamar al servicio de login
require 'services/userService.php';   // Llamar al servicio de usuarios

$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? null;

switch ($endpoint) {
    case 'login':
        handleLoginRequest($method);
        break;
    case 'usuarios':
        handleUsuariosRequest($method);
        break;
    default:
        echo json_encode(['status' => 'error', 'message' => 'Endpoint no encontrado']);
        break;
}

function handleLoginRequest($method) {
    if ($method == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $email = $data['email'] ?? '';
        $contrasena = $data['contrasena'] ?? '';
        
        $response = login($email, $contrasena);
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    }
}

function handleUsuariosRequest($method) {
    if ($method == 'GET') {
        $usuarios = getUsuarios();
        echo json_encode($usuarios);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    }
}
?>
