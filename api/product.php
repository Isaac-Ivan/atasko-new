<?php
session_start();
require_once '../config/db.php';

// --- Headers ---
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- Respuesta por defecto ---
$response = ['status' => 'error', 'message' => 'Acción no válida o sin permisos.'];

// --- Verificar que el usuario sea Administrador ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    http_response_code(403); // Forbidden
    $response['message'] = 'Acceso denegado. Se requiere rol de Administrador.';
    echo json_encode($response);
    exit();
}

$action = $_GET['action'] ?? null;

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    handleDelete($conn);
} else {
    echo json_encode($response);
}

$conn->close();

/**
 * Maneja la eliminación de un producto y sus entradas relacionadas en el carrito.
 */
function handleDelete($conn) {
    $data = json_decode(file_get_contents("php://input"));
    $response = ['status' => 'error', 'message' => 'ID de producto inválido.'];

    if (!isset($data->idproducto) || !is_numeric($data->idproducto)) {
        http_response_code(400); // Bad Request
        echo json_encode($response);
        return;
    }

    $idproducto = (int)$data->idproducto;

    // Iniciar transacción para garantizar que ambas eliminaciones se completen
    $conn->begin_transaction();

    try {
        // Eliminar de los carritos de compras (detalle_carrito)
        $stmt_delete_details = $conn->prepare("DELETE FROM detalle_carrito WHERE idproducto = ?");
        $stmt_delete_details->bind_param("i", $idproducto);
        $stmt_delete_details->execute();
        $stmt_delete_details->close();

        // Eliminar el producto principal
        $stmt_delete_product = $conn->prepare("DELETE FROM producto WHERE idproducto = ?");
        $stmt_delete_product->bind_param("i", $idproducto);
        
        if ($stmt_delete_product->execute()) {
            if ($stmt_delete_product->affected_rows > 0) {
                $response = ['status' => 'success', 'message' => 'Producto eliminado correctamente.'];
                $conn->commit();
            } else {
                throw new Exception('El producto no fue encontrado o ya fue eliminado.');
            }
        } else {
            throw new Exception('No se pudo eliminar el producto.');
        }
        $stmt_delete_product->close();

    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500); // Internal Server Error
        $response['message'] = 'Error en el servidor: ' . $e->getMessage();
    }

    echo json_encode($response);
}
?>
