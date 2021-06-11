<?php
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/lentDaoMS.php';
require_once '../config.php';
session_start();

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

$lentDao = new lentDAOMS($pdo);
$isLent = $lentDao->checkIfIsLent($_POST['selectedEquipment']);

var_dump($_POST);
if(isset($_POST['responsibleUser']) && isset($_POST['selectedEquipment'])) {
    if(!$isLent) {
        if(checkFullDate($_POST['initialDate']) && checkFullDate($_POST['finalDate'])) { //Se não existir ou se não for válida
            $newLent = new lent();
            $newLent->setUser($_POST['responsibleUser']);
            $newLent->setInitialDate($_POST['initialDate']);
            $newLent->setFinalDate($_POST['finalDate']);
            $newLent->setContact($_POST['contact']);
            $newLent->setObs($_POST['obs']);
            $newLent->setEquipmentId($_POST['selectedEquipment']);

            $lentDao->createLent($newLent);
            unset($_SESSION['lentEquipmentError']);
        } else {
            $_SESSION['lentEquipmentError'] = "As datas inseridas não são validas.";
        }
    } else {
        $_SESSION['lentEquipmentError'] = "Este equipamento já está emprestado.";
    }
} else {
    $_SESSION['lentEquipmentError'] = "Não foram inseridos todos os dados necessários.";
}

