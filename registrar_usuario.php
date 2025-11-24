<?php
header('Content-Type: application/json');

//Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexión a la base de datos
$servername = "sql102.infinityfree.com";
$username = "if0_40479302";
$password = "Tecnologias03";
$dbname = "if0_40479302_tecnologias_de_internet";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]));
}

// Capturar los datos enviados desde el formulario
$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data["name"];
$apellido = $data["lastname"];
$correo = $data["email"];
$usuario = $data["user"];
$contraseña = password_hash($data["password"], PASSWORD_BCRYPT);
$fecha = $data["bornDate"];
$pais = $data["country"];
$telefono = $data["phoneNumber"];

// Verificar si el correo ya existe
$check = $conn->prepare("SELECT correo FROM usuarios WHERE correo = ?");
$check->bind_param("s", $correo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo ya está registrado."]);
    $check->close();
    $conn->close();
    exit;
}

$check->close();

// Insertar nuevo registro

$stmt1 = $conn->prepare("INSERT INTO usuarios (Correo, Nombres, Apellidos, Nombre_usuario, Contrasena, Fecha_nacimiento, Pais, Telefono) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt1->bind_param("ssssssss", $correo, $nombre, $apellido, $usuario, $contraseña, $fecha, $pais, $telefono);

if (!$stmt1->execute()) {
    echo json_encode(["success" => false, "message" => "Error al registrar usuario: " . $stmt1->error]);
    exit;
}
$stmt1->close();

$stmt2 = $conn->prepare("INSERT INTO imagenes (Correo) VALUES (?)");
$stmt2->bind_param("s", $correo);
$stmt2->execute();
$stmt2->close();

echo json_encode(["success" => true, "message" => "Usuario registrado exitosamente."]);

$conn->close();
?>
