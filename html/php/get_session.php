<?php
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["usuario_correo"])) {
    echo json_encode(["logged" => false]);
    exit;
}

echo json_encode([
    "logged" => true,
    "correo" => $_SESSION["usuario_correo"],
    "foto" => $_SESSION["usuario_foto"]
]);
?>