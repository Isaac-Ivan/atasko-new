<?php
session_start();

require_once '../config/db.php';

// Permitir peticiones desde cualquier origen (CORS) y permitir cookies/sesiones
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");

// Manejar peticiones OPTIONS (pre-flight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = array('status' => 'error', 'message' => 'Petición inválida.');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->correo) || !isset($data->password)) {
        $response['message'] = 'Por favor, ingrese correo y contraseña.';
    } else {
        $correo = $conn->real_escape_string($data->correo);
        $password = $data->password; // No se escapa para la comparación (y futuro hash)

        $sql = "SELECT idusuario, nombre, correo, password, idrol FROM usuario WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // -- Verificación de contraseña segura con HASH --
            // Compara la contraseña enviada por el usuario con el hash guardado en la DB.
            if (password_verify($password, $user['password'])) {
                // Credenciales correctas. Iniciar sesión.
                $_SESSION['user_id'] = $user['idusuario'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role'] = $user['idrol'];

                $response['status'] = 'success';
                $response['message'] = 'Inicio de sesión exitoso.';
                $response['data'] = array(
                    'id' => $user['idusuario'],
                    'nombre' => $user['nombre'],
                    'correo' => $user['correo']
                );
            } else {
                $response['message'] = 'La contraseña es incorrecta.';
            }
        } else {
            $response['message'] = 'No se encontró un usuario con ese correo.';
        }
        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
