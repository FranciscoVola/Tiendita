<?php
header("Access-Control-Allow-Origin: *"); // Permitir acceso desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Encabezados permitidos
header("Content-Type: application/json; charset=UTF-8"); // Establecer tipo de respuesta

// Manejo de preflight (método OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once './conexion.php';

try {
    // Verificar si se proporcionó el parámetro 'id'
    if (!isset($_GET['id'])) {
        http_response_code(400); // Solicitud incorrecta
        echo json_encode(["error" => "Falta el parámetro 'id'."]);
        exit;
    }

    $id = $_GET['id']; // Obtener el id de la solicitud

    // Validar que el id sea un número entero
    if (!filter_var($id, FILTER_VALIDATE_INT)) {
        http_response_code(400); // Solicitud incorrecta
        echo json_encode(["error" => "El parámetro 'id' debe ser un número entero."]);
        exit;
    }

    // Conexión directa utilizando PDO
    $pdo = new PDO("mysql:host=localhost;dbname=tienda;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Preparar y ejecutar la consulta
    $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener el producto
    $producto = $stmt->fetch();

    // Verificar si se encontró el producto
    if (!$producto) {
        http_response_code(404); // No encontrado
        echo json_encode(["error" => "Producto no encontrado."]);
        exit;
    }

    // Devolver el producto en formato JSON
    echo json_encode($producto);
} catch (PDOException $e) {
    // Manejo de errores
    http_response_code(500); // Error interno del servidor
    echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
}
?>
