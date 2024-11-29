<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

include '../conexion.php';

// Crear conexión
$conexion = new Conexion();
$pdo = $conexion->conectar();

// Obtener datos del formulario
$data = json_decode(file_get_contents("php://input"));
if (!$data) {
    echo json_encode(["success" => false, "message" => "Datos no válidos."]);
    exit;
}

$nombre = $data->nombre ?? null;
$email = $data->email ?? null;
$password = $data->password ?? null;
$rol = "usuario";

// Validar campos
if (empty($nombre) || empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Todos los campos son requeridos."]);
    exit;
}

try {
    // Verificar si el correo ya está registrado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está registrado."]);
        exit;
    }

    // Hashear la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario en la base de datos
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $email, $hashedPassword, $rol]);

    echo json_encode(["success" => true, "message" => "Usuario registrado exitosamente."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error al registrar usuario: " . $e->getMessage()]);
}
