<?php
require_once "conexion.php";
session_start();
header("Content-Type: application/json");

//Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['usuario_correo'])) {
    die("Error: No hay sesión iniciada.");
}

// Ruta donde se va a guardar la imagen final
$destino = "images/";

if (!isset($_FILES['portfolioImage4'])) {
    echo "No se recibió ninguna imagen";
    exit;
}

$file = $_FILES['portfolioImage4'];
$originalName = basename($file["name"]);
$tmp = $file["tmp_name"];

// Validar que sea realmente una imagen
$info = getimagesize($tmp);
if ($info === false) {
    echo "El archivo no es una imagen válida";
    exit;
}

// Verificar permisos de escritura
if (!is_writable(dirname($destino))) {
    echo "Error: la carpeta $destino no permite escritura";
    exit;
}

// Crear ruta completa
$destino = $destino . $originalName;

// Guardar la nueva imagen
if (move_uploaded_file($tmp, $destino)) {

    $usuario = $_SESSION['usuario_correo'];
    $query = $conn->prepare("UPDATE imagenes SET Digital4 = ? WHERE Correo = ?;");
    $query->bind_param("ss", $originalName, $usuario);
    $query->execute();
    $_SESSION["usuario_digital4"] = $originalName;

    echo "Imagen cargada correctamente";
} else {
    echo "Error al guardar la imagen";
}

?>
