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
    "foto" => $_SESSION["usuario_foto"],
    "digital1" => $_SESSION["usuario_digital1"],
    "digital2" => $_SESSION["usuario_digital2"],
    "digital3" => $_SESSION["usuario_digital3"],
    "digital4" => $_SESSION["usuario_digital4"],
    "mural" => $_SESSION["usuario_mural"],
    "comision" => $_SESSION["usuario_comision"],
    "inicio" => $_SESSION["inicios"],
    "nick" => $_SESSION["usuario_nick"]
]);
?>