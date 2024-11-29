<?php
header("Access-Control-Allow-Origin: *"); // Permitir acceso desde cualquier origen
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Encabezados permitidos
header("Content-Type: application/json; charset=UTF-8"); // Establecer tipo de respuesta

require_once './conexion.php';

try {
    // Conexión directa utilizando PDO
    $pdo = new PDO("mysql:host=localhost;dbname=tienda;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Determinar el método HTTP
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            obtenerProductos($pdo);
            break;
        case 'POST':
            crearProducto($pdo);
            break;
        case 'PUT':
            actualizarProducto($pdo);
            break;
        case 'DELETE':
            eliminarProducto($pdo);
            break;
        default:
            echo json_encode(["success" => false, "message" => "Método no soportado"]);
            break;
    }
} catch (PDOException $e) {
    // Manejo de errores
    http_response_code(500); // Error interno del servidor
    echo json_encode(["error" => "Error en el servidor: " . $e->getMessage()]);
}

// Función para obtener productos
function obtenerProductos($pdo) {
    try {
        $sql = "SELECT id, nombre, descripcion, precio, imagen FROM productos";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar la URL completa de la imagen a cada producto
        $base_url = "http://localhost/tienda/tiendita/public/img/";
        foreach ($productos as &$producto) {
            $producto['imagen'] = $base_url . basename($producto['imagen']);
        }

        echo json_encode($productos);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener productos: " . $e->getMessage()]);
    }
}

// Función para crear un nuevo producto
function crearProducto($pdo) {
    $data = json_decode(file_get_contents("php://input"));
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Datos no válidos"]);
        return;
    }

    $nombre = $data->nombre ?? null;
    $imagen = $data->imagen ?? null;
    $precio = $data->precio ?? null;
    $descripcion = $data->descripcion ?? null;

    if (!$nombre || !$precio || !$descripcion) {
        echo json_encode(["success" => false, "message" => "Campos obligatorios faltantes"]);
        return;
    }

    try {
        $sql = "INSERT INTO productos (nombre, imagen, precio, descripcion) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $imagen, $precio, $descripcion]);
        echo json_encode(["success" => true, "message" => "Producto creado con éxito"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al crear producto: " . $e->getMessage()]);
    }
}

// Función para actualizar un producto existente
function actualizarProducto($pdo) {
    $data = json_decode(file_get_contents("php://input"));
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Datos no válidos"]);
        return;
    }

    $id = $data->id ?? null;
    $nombre = $data->nombre ?? null;
    $imagen = $data->imagen ?? null;
    $precio = $data->precio ?? null;
    $descripcion = $data->descripcion ?? null;

    if (!$id || !$nombre || !$precio || !$descripcion) {
        echo json_encode(["success" => false, "message" => "Campos obligatorios faltantes"]);
        return;
    }

    try {
        $sql = "UPDATE productos SET nombre = ?, imagen = ?, precio = ?, descripcion = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $imagen, $precio, $descripcion, $id]);
        echo json_encode(["success" => true, "message" => "Producto actualizado con éxito"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al actualizar producto: " . $e->getMessage()]);
    }
}

// Función para eliminar un producto
function eliminarProducto($pdo) {
    $data = json_decode(file_get_contents("php://input"));
    if (!$data || !isset($data->id)) {
        echo json_encode(["success" => false, "message" => "ID de producto no proporcionado"]);
        return;
    }

    $id = $data->id;

    try {
        $sql = "DELETE FROM productos WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Producto eliminado con éxito"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al eliminar producto: " . $e->getMessage()]);
    }
}
?>
