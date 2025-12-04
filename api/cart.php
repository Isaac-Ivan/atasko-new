<?php
session_start();
require_once '../config/db.php';

// --- Headers ---
header("Access-Control-Allow-Origin: *"); // En producción, restringir a tu dominio
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- Respuesta por defecto ---
$response = ['status' => 'error', 'message' => 'Acción no válida o usuario no autenticado.'];

// --- Verificar autenticación ---
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode($response);
    exit();
}

$idusuario = $_SESSION['user_id'];
$action = $_GET['action'] ?? null;

// --- Enrutador de acciones ---
switch ($action) {
    case 'add':
        handleAdd($conn, $idusuario);
        break;
    case 'get':
        handleGet($conn, $idusuario);
        break;
    case 'update':
        handleUpdate($conn, $idusuario);
        break;
    case 'remove':
        handleRemove($conn, $idusuario);
        break;
    default:
        echo json_encode($response);
        break;
}

$conn->close();

// --- Lógica para ACTUALIZAR cantidad ---
function handleUpdate($conn, $idusuario) {
    $data = json_decode(file_get_contents("php://input"));
    $response = ['status' => 'error', 'message' => 'Datos inválidos.'];

    if (!isset($data->idproducto) || !isset($data->cantidad) || !is_numeric($data->idproducto) || !is_numeric($data->cantidad) || $data->cantidad < 0) {
        echo json_encode($response);
        return;
    }

    $idproducto = (int)$data->idproducto;
    $cantidad = (int)$data->cantidad;

    // Si la cantidad es 0, lo tratamos como un 'remove'
    if ($cantidad === 0) {
        handleRemove($conn, $idusuario);
        return;
    }

    $stmt = $conn->prepare("UPDATE detalle_carrito dc JOIN carrito c ON dc.idcarrito = c.idcarrito SET dc.cantidad = ? WHERE c.idusuario = ? AND dc.idproducto = ? AND c.estado = 1");
    $stmt->bind_param("iii", $cantidad, $idusuario, $idproducto);
    
    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Cantidad actualizada.'];
    } else {
        $response['message'] = 'No se pudo actualizar la cantidad.';
    }
    $stmt->close();
    echo json_encode($response);
}

// --- Lógica para ELIMINAR producto ---
function handleRemove($conn, $idusuario) {
    $data = json_decode(file_get_contents("php://input"));
    $response = ['status' => 'error', 'message' => 'ID de producto inválido.'];

    if (!isset($data->idproducto) || !is_numeric($data->idproducto)) {
        echo json_encode($response);
        return;
    }

    $idproducto = (int)$data->idproducto;

    $stmt = $conn->prepare("DELETE FROM detalle_carrito WHERE idproducto = ? AND idcarrito = (SELECT idcarrito FROM carrito WHERE idusuario = ? AND estado = 1)");
    $stmt->bind_param("ii", $idproducto, $idusuario);

    if ($stmt->execute()) {
        $response = ['status' => 'success', 'message' => 'Producto eliminado del carrito.'];
    } else {
        $response['message'] = 'No se pudo eliminar el producto.';
    }
    $stmt->close();
    echo json_encode($response);
}

// --- Lógica para OBTENER el carrito ---
function handleGet($conn, $idusuario) {
    $response = ['status' => 'error', 'message' => 'No se pudo obtener el carrito.'];

    $sql = "SELECT 
                p.idproducto,
                p.nombre,
                p.imagen,
                dc.cantidad,
                dc.precio_unitario
            FROM detalle_carrito dc
            JOIN producto p ON dc.idproducto = p.idproducto
            JOIN carrito c ON dc.idcarrito = c.idcarrito
            WHERE c.idusuario = ? AND c.estado = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idusuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    $stmt->close();
    
    $response['status'] = 'success';
    $response['message'] = 'Carrito obtenido con éxito.';
    $response['data'] = $items;

    echo json_encode($response);
}

// --- Lógica para añadir producto ---
function handleAdd($conn, $idusuario) {
    $data = json_decode(file_get_contents("php://input"));
    $response = ['status' => 'error', 'message' => 'Datos de producto inválidos.'];

    if (!isset($data->idproducto) || !isset($data->cantidad) || !is_numeric($data->idproducto) || !is_numeric($data->cantidad) || $data->cantidad <= 0) {
        echo json_encode($response);
        return;
    }

    $idproducto = (int)$data->idproducto;
    $cantidad_a_anadir = (int)$data->cantidad;

    // Iniciar transacción para garantizar consistencia
    $conn->begin_transaction();

    try {
        // 1. Obtener o crear un carrito activo
        $idcarrito = null;
        $stmt_get_cart = $conn->prepare("SELECT idcarrito FROM carrito WHERE idusuario = ? AND estado = 1");
        $stmt_get_cart->bind_param("i", $idusuario);
        $stmt_get_cart->execute();
        $result_cart = $stmt_get_cart->get_result();

        if ($row = $result_cart->fetch_assoc()) {
            $idcarrito = $row['idcarrito'];
        } else {
            // Si no hay carrito, creamos uno nuevo con estado 1
            $stmt_create_cart = $conn->prepare("INSERT INTO carrito (idusuario, estado) VALUES (?, 1)");
            $stmt_create_cart->bind_param("i", $idusuario);
            $stmt_create_cart->execute();
            $idcarrito = $conn->insert_id;
            $stmt_create_cart->close();
        }
        $stmt_get_cart->close();

        if (!$idcarrito) {
            throw new Exception("No se pudo obtener o crear el carrito.");
        }

        // 2. Verificar si el producto ya está en el carrito
        $iddetalle = null;
        $stmt_check_item = $conn->prepare("SELECT iddetalle FROM detalle_carrito WHERE idcarrito = ? AND idproducto = ?");
        $stmt_check_item->bind_param("ii", $idcarrito, $idproducto);
        $stmt_check_item->execute();
        $result_item = $stmt_check_item->get_result();
        if ($row = $result_item->fetch_assoc()) {
            $iddetalle = $row['iddetalle'];
        }
        $stmt_check_item->close();

        // 3. Insertar o actualizar el detalle del carrito
        if ($iddetalle) { // Actualizar cantidad
            $stmt_update = $conn->prepare("UPDATE detalle_carrito SET cantidad = cantidad + ? WHERE iddetalle = ?");
            $stmt_update->bind_param("ii", $cantidad_a_anadir, $iddetalle);
            $stmt_update->execute();
            $stmt_update->close();
        } else { // Insertar nuevo item
            $stmt_insert = $conn->prepare("INSERT INTO detalle_carrito (idcarrito, idproducto, cantidad, precio_unitario) SELECT ?, ?, ?, precio FROM producto WHERE idproducto = ?");
            $stmt_insert->bind_param("iiii", $idcarrito, $idproducto, $cantidad_a_anadir, $idproducto);
            $stmt_insert->execute();
            $stmt_insert->close();
        }

        $conn->commit();
        $response = ['status' => 'success', 'message' => 'Producto añadido al carrito.'];

    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = 'Error en la base de datos: ' . $e->getMessage();
    }

    echo json_encode($response);
}
?>
