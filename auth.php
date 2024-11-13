<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAuthenticated() {
    return isset($_SESSION['email']);
}

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function isValidSession() {
    global $cnnPDO;
    if (!isset($_SESSION['email'], $_SESSION['session_key'])) {
        return false;
    }

    $stmt = $cnnPDO->prepare("SELECT session_key FROM usuarios WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $storedSessionKey = $stmt->fetchColumn();

    return $storedSessionKey === $_SESSION['session_key'];
}

function protectRoute($role) {
    if (!isAuthenticated() || !hasRole($role) || !isValidSession()) {
        header("Location: index.php");
        exit();
    }
}
?>
