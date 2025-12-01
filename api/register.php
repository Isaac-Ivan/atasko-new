<?php
require_once '../config/db.php';

// Permitir peticiones desde cualquier origen (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = array('status' => 'error', 'message' => 'Petición inválida.');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Validacion de datos
    if (!isset($data->nombre) || !isset($data->correo) || !isset($data->password)) {
        $response['message'] = 'Todos los campos son requeridos.';
    } elseif (strlen($data->password) < 6) {
        $response['message'] = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif (!filter_var($data->correo, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'El formato del correo no es válido.';
    } else {
        $nombre = $conn->real_escape_string($data->nombre);
        $correo = $conn->real_escape_string($data->correo);
        
        // --- HASHING DE CONTRASEÑA ---
        $password_hash = password_hash($data->password, PASSWORD_BCRYPT);
        
        // Rol por defecto para nuevos usuarios (ej: 2 = Cliente)
        $idrol = 2; 

        // Verificar si el correo ya existe
        $check_sql = "SELECT idusuario FROM usuario WHERE correo = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $correo);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $response['message'] = 'El correo electrónico ya está registrado.';
        } else {
            // Insertar nuevo usuario
            $insert_sql = "INSERT INTO usuario (nombre, correo, password, idrol) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("sssi", $nombre, $correo, $password_hash, $idrol);

            if ($insert_stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Usuario registrado con éxito. Ahora puede iniciar sesión.';
            } else {
                $response['message'] = 'Error al registrar el usuario. Por favor, intente de nuevo.';
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
