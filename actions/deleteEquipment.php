<?php
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';
session_start();

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $equipments = new equipmentsDAOMS($pdo);

    $equipmentModel = new equipments();
    $equipmentModel->setId($_GET['id']);

    $equipments->deleteHistoric($equipmentModel);
    $status = $equipments->deleteEquipment($equipmentModel);

    if($status == 'error') {
        $_SESSION['indexErrorMessage'] = "O equipamento está registrado em algum lugar, remova-o primeiro.";
        header('Location: ../index.php');
        die();
    }

    $_SESSION['successMessage'] = "O equipamento " . $_GET['id'] . " foi apagada com sucesso.";
} else {
    $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
}

header('Location: ../index.php');
die();