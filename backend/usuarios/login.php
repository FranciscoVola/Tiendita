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

$email = $data->email ?? null;
$password = $data->password ?? null;

// Validar campos
if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Correo y contraseña son requeridos."]);
    exit;
}

try {
    // Verificar si el usuario existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $response = [
            "success" => true,
            "message" => "Inicio de sesión exitoso.",
            "user" => [
                "id" => $user['id'],
                "nombre" => $user['nombre'],
                "email" => $user['email'],
                "rol" => $user['rol']
            ]
        ];

        // Validar si el usuario es administrador
        if ($user['rol'] === 'admin') {
            $response['message'] = "Bienvenido, administrador.";
            $response['admin_access'] = true; // Indicador para el frontend
        }

        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Correo o contraseña incorrectos."]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en el servidor: " . $e->getMessage()]);
}
?>
