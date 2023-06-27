<?php
session_start();

if(!isset($_SESSION['user_name'])) {
  header('Location: login.php');
  exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tradedbn";

$user_id = $_SESSION['user_id'];
$tipo = $_POST['tipo'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$fechaHora = $_POST['fechaHora'];
$indexname = $_POST['indexname'];
$total = $_POST['total'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $consultaExistencia = $conn->prepare('SELECT ID FROM acciones WHERE ID_usuario = :iduser AND MATCH(Nombre) AGAINST(:nombre)');
    $consultaExistencia->bindParam(':iduser', $user_id);
    $consultaExistencia->bindParam(':nombre', $indexname);
    $consultaExistencia->execute();
    $rowexis = $consultaExistencia->fetch(PDO::FETCH_ASSOC);

    if ($rowexis) {
        $consultaUpdate = $conn->prepare('UPDATE acciones SET Cantidad = Cantidad + :valor WHERE ID = :id');
        $consultaUpdate->bindParam(':valor', $cantidad);
        $consultaUpdate->bindParam(':id', $rowexis['ID']);
        $consultaUpdate->execute();
    } else {
        $consultaInsert = $conn->prepare('INSERT INTO acciones (ID_usuario, Nombre, Cantidad) VALUES (:id_user, :nombre, :valor)');
        $consultaInsert->bindParam(':id_user', $user_id);
        $consultaInsert->bindParam(':nombre', $indexname);
        $consultaInsert->bindParam(':valor', $cantidad);
        $consultaInsert->execute();
    }

    $consultasal = $conn->prepare('UPDATE usuarios SET Saldo = Saldo + :valor WHERE ID = :user_id');
    $consultasal->bindParam(':valor', $total);
    $consultasal->bindParam(':user_id', $user_id);
    $consultasal->execute();

    $sql = "INSERT INTO transacciones (ID_usuario, Accion, Tipo, Cantidad, Precio, MontoTotal, FechaHora) VALUES (:iduser, :accion, :tipo, :cantidad, :precio, :total, :fechaHora)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':iduser', $user_id);
    $stmt->bindParam(':accion', $indexname);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':cantidad', $cantidad);
    $stmt->bindParam(':precio', $precio);
    $stmt->bindParam(':total', $total);
    $stmt->bindParam(':fechaHora', $fechaHora);

    if ($stmt->execute()) {
        $response = "Transacción registrada exitosamente";
        echo $response;
    } else {
        $response = "Error al registrar la transacción";
        echo $response;
    }
} catch(PDOException $e) {
    $response = "Error en la conexión o consulta: " . $e->getMessage();
    echo $response;
}

$conn = null;
?>
