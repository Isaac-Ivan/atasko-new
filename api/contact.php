<?php
require_once '../config/db.php';

// Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = ['status' => 'error', 'message' => 'Petición inválida.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Validaciones
    if (!isset($data->nombre) || !isset($data->correo) || !isset($data->mensaje)) {
        $response['message'] = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($data->correo, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'El correo electrónico no es válido.';
    } elseif (strlen(trim($data->nombre)) < 2) {
        $response['message'] = 'El nombre es demasiado corto.';
    } elseif (strlen(trim($data->mensaje)) < 10) {
        $response['message'] = 'El mensaje debe tener al menos 10 caracteres.';
    } else {
        $nombre = $conn->real_escape_string(trim($data->nombre));
        $correo = $conn->real_escape_string(trim($data->correo));
        $mensaje = $conn->real_escape_string(trim($data->mensaje));

        $stmt = $conn->prepare("INSERT INTO mensajes (nombre, correo, mensaje) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre, $correo, $mensaje);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = '¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.';
        } else {
            $response['message'] = 'Hubo un error al guardar tu mensaje. Por favor, intenta de nuevo.';
        }
        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
