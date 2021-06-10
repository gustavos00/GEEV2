<?php
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $equipments = new equipmentsDAOMS($pdo);

    $newEquipment = new equipments();
    $newEquipment->setId($_GET['id']);

    $equipments->deleteEquipment($newEquipment);
}    

header('Location: ../index.php');
die();