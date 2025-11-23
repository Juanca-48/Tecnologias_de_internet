<?php
session_start();
header('Content-Type: application/json');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tecnologias_de_internet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión con la base de datos."]));
}

// Leer datos del cuerpo de la petición (formato JSON)
$data = json_decode(file_get_contents("php://input"), true);

$correo = $data["email"];
$clave = $data["password"];

// Buscar el usuario por correo
$stmt = $conn->prepare("SELECT Contraseña FROM usuarios WHERE Correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "El correo no está registrado."]);
    $stmt->close();
    $conn->close();
    exit;
}

// Obtener la contraseña almacenada
$stmt->bind_result($contraseñaHash);
$stmt->fetch();

// Verificar la contraseña ingresada
if (password_verify($clave, $contraseñaHash)) {
    
    $stmt = $conn->prepare("SELECT Perfil, Digital1, Digital2, Digital3, Digital4 FROM imagenes WHERE Correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($Perfil, $Digital1, $Digital2, $Digital3, $Digital4);
    $stmt->fetch();

    //Guardar datos en la sesion
    $_SESSION["usuario_correo"] = $correo;
    $_SESSION["usuario_foto"] = $Perfil;
    $_SESSION["usuario_digital1"] = $Digital1;
    $_SESSION["usuario_digital2"] = $Digital2;
    $_SESSION["usuario_digital3"] = $Digital3;
    $_SESSION["usuario_digital4"] = $Digital4;
    
    //Mostrar mensaje
    echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso."]);
} else {
    echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
}

$stmt->close();
//$conn->close();
?>
