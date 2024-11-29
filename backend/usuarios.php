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
            obtenerUsuarios($pdo);
            break;
        case 'POST':
            crearUsuario($pdo);
            break;
        case 'PUT':
            actualizarUsuario($pdo);
            break;
        case 'DELETE':
            eliminarUsuario($pdo);
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

// Función para obtener usuarios
function obtenerUsuarios($pdo) {
    try {
        $sql = "SELECT id, nombre, email, rol FROM usuarios";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al obtener usuarios: " . $e->getMessage()]);
    }
}

// Función para crear un nuevo usuario
function crearUsuario($pdo) {
    $data = json_decode(file_get_contents("php://input"));
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Datos no válidos"]);
        return;
    }

    $nombre = $data->nombre ?? null;
    $email = $data->email ?? null;
    $password = $data->password ?? null;
    $rol = $data->rol ?? 'user'; // Valor por defecto: 'user'

    if (!$nombre || !$email || !$password) {
        echo json_encode(["success" => false, "message" => "Campos obligatorios faltantes"]);
        return;
    }

    try {
        // Verificar si el correo ya existe
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            echo json_encode(["success" => false, "message" => "El correo ya está registrado"]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Encriptar la contraseña

        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $email, $passwordHash, $rol]);

        echo json_encode(["success" => true, "message" => "Usuario creado con éxito"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al crear usuario: " . $e->getMessage()]);
    }
}

// Función para actualizar un usuario existente
function actualizarUsuario($pdo) {
    $data = json_decode(file_get_contents("php://input"));
    if (!$data) {
        echo json_encode(["success" => false, "message" => "Datos no válidos"]);
        return;
    }

    $id = $data->id ?? null;
    $nombre = $data->nombre ?? null;
    $email = $data->email ?? null;
    $password = $data->password ?? null;
    $rol = $data->rol ?? null;

    if (!$id || !$nombre || !$email) {
        echo json_encode(["success" => false, "message" => "Campos obligatorios faltantes"]);
        return;
    }

    try {
        // Verificar si el correo ya está en uso por otro usuario
        $sql = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email, $id]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            echo json_encode(["success" => false, "message" => "El correo ya está en uso por otro usuario"]);
            return;
        }

        $sql = "UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $email, $rol, $id]);

        // Actualizar la contraseña si se proporciona
        if ($password) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$passwordHash, $id]);
        }

        echo json_encode(["success" => true, "message" => "Usuario actualizado con éxito"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al actualizar usuario: " . $e->getMessage()]);
    }
}

// Función para eliminar un usuario
function eliminarUsuario($pdo) {
    $data = json_decode(file_get_contents("php://input"));
    if (!$data || !isset($data->id)) {
        echo json_encode(["success" => false, "message" => "ID de usuario no proporcionado"]);
        return;
    }

    $id = $data->id;

    try {
        // Eliminar el usuario
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "Usuario eliminado con éxito"]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error al eliminar usuario: " . $e->getMessage()]);
    }
}
?>
