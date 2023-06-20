<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tradedb";

$tipo = $_POST['tipo'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];
$fechaHora = $_POST['fechaHora'];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "INSERT INTO Transacciones (Tipo, Cantidad, Precio, FechaHora) VALUES (:tipo, :cantidad, :precio, :fechaHora)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':cantidad', $cantidad);
    $stmt->bindParam(':precio', $precio);
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
