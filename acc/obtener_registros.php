<?php
session_start();
if(!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['user_id'];

include_once 'db.php';

$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$accion = isset($_POST['accion']) ? $_POST['accion'] : '';

$resultsPerPage = 5;
$offset = ($page - 1) * $resultsPerPage;

$sql = "SELECT Accion, Tipo, FechaHora, Cantidad, Precio, MontoTotal FROM transacciones WHERE ID_usuario = :userID";

$params = array(':userID' => $userID);

if (!empty($fecha)) {
    $sql .= " AND DATE(FechaHora) = :fecha";
    $params[':fecha'] = $fecha;
}

if (!empty($accion)) {
    $sql .= " AND MATCH(Accion) AGAINST(:accion)";
    $params[':accion'] = $accion;
}

$sql .= " ORDER BY FechaHora DESC LIMIT $offset, $resultsPerPage";

try {
    $stmt = $db->prepare($sql);

    $stmt->execute($params);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($results);
} catch (PDOException $e) {
    die("Error al ejecutar la consulta: " . $e->getMessage());
}
?>
