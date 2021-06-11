<?php
require_once '../config.php';
require_once '../dao/malfunctionsDaoMS.php';
session_start();

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $equipments = new malfunctionsDAOMS($pdo);

    $newEquipment = new malfunction();
    $newEquipment->setId($_GET['id']);

    $equipments->deleteMalfunction($newEquipment);
    $_SESSION['successMessage'] = "A assistência " . $_GET['id'] . " foi apagada com sucesso.";
}    

header('Location: ../index.php');
die();