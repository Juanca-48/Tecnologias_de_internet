<?php
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
    echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso."]);
} else {
    echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
}

$stmt->close();
$conn->close();
?>
