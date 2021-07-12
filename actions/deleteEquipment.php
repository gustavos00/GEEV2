<?php
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';
session_start();

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $equipments = new equipmentsDAOMS($pdo);
    $equipmentStatus = $equipments->checkDeleteStatus($_GET['id']);
    
    if(!$equipmentStatus) {
        $equipmentModel = new equipments();
        $equipmentModel->setId($_GET['id']);

        $equipments->deleteEquipment($equipmentModel);
        $_SESSION['successMessage'] = "O equipamento " . $_GET['id'] . " foi apagada com sucesso.";    
    } else {
        $_SESSION['indexErrorMessage'] = "Este equipamento está registrado em alguns lugares, remova-o primeiro.";
    }

    
} else {
    $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
}

