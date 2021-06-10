<?php 
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/categorysDaoMS.php';

if(isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $equipments = new equipmentsDAOMS($pdo);

    $categorys = new categorysDAOMS($pdo);
    $categoryId = $categorys->getRetiredCategoryId();

    $newEquipment = new equipments();
    $newEquipment->setId($_GET['id']);

    $equipments->setEquipmentAsRetired($newEquipment, $categoryId);

    header('Location: ../index.php');
    die();
}