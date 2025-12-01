<?php
// Incluir el archivo de configuracion de la base de datos
require_once '../config/db.php';

// Permitir el acceso desde cualquier origen (CORS)
header("Access-Control-Allow-Origin: *");

$response = array(
    'status' => 'error',
    'message' => 'No se encontraron productos.'
);

// Consulta para obtener todos los productos
$sql = "SELECT idproducto, nombre, descripcion, precio, stock, imagen FROM producto";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $productos = array();
    while($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    $response['status'] = 'success';
    $response['message'] = 'Productos obtenidos correctamente.';
    $response['data'] = $productos;
}

// Cerrar la conexion
$conn->close();

// Devolver la respuesta en formato JSON
echo json_encode($response);
?>