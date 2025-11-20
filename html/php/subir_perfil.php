<?php
session_start();

// ID del usuario logueado (debes ajustarlo según tu login)
$user_id = $_SESSION['user_id'];

$targetDir = "uploads/profile_pics/";
$allowedTypes = ['jpg','jpeg','png','gif'];

// Verifica que exista imagen
if (!isset($_FILES['profile_pic'])) {
    echo json_encode(["success" => false, "message" => "No se recibió imagen"]);
    exit;
}

$fileName = basename($_FILES["profile_pic"]["name"]);
$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(["success" => false, "message" => "Formato no permitido"]);
    exit;
}

// Generar nombre único
$newName = "profile_" . $user_id . "_" . time() . "." . $fileType;
$targetPath = $targetDir . $newName;

// Mover archivo
if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetPath)) {

    // Actualizar en BD
    require "conexion.php";
    $sql = "UPDATE usuarios SET foto_perfil = '$newName' WHERE id = $user_id";
    mysqli_query($conn, $sql);

    echo json_encode([
        "success" => true, 
        "newPath" => $targetPath
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Error al guardar archivo"]);
}
?>
