<?php
session_start();

if(!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

include_once 'db.php';

try {
    $user_id = $_SESSION['user_id'];
    $indexname = $_POST['indexname'];

    $consultaDinero = $db->prepare('SELECT Saldo FROM usuarios WHERE ID = :user_id');
    $consultaDinero->bindParam(':user_id', $user_id);
    $consultaDinero->execute();
    $resultadoDinero = $consultaDinero->fetch(PDO::FETCH_ASSOC);

    $consultaAcciones = $db->prepare('SELECT Cantidad FROM acciones WHERE ID_usuario = :user_id AND MATCH(Nombre) AGAINST(:nombre)');
    $consultaAcciones->bindParam(':user_id', $user_id);
    $consultaAcciones->bindParam(':nombre', $indexname);
    $consultaAcciones->execute();
    $resultadoAcciones = $consultaAcciones->fetch(PDO::FETCH_ASSOC);

    if(!$resultadoAcciones) $resultadoAcciones['Cantidad'] = 0.00;

    $resultadoArray = array(
        'dinero' => $resultadoDinero['Saldo'],
        'acciones' => $resultadoAcciones['Cantidad']
    );

    echo json_encode($resultadoArray);

} catch (PDOException $e) {
    echo 'Error al obtener los datos del usuario: ' . $e->getMessage();
}

?>