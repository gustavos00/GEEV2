<?php
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/lentDaoMS.php';
require_once '../config.php';
session_start();

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

function checkInput($i) {
    return (trim($i) != "");
}

$lentDao = new lentDAOMS($pdo);
$isLent = $lentDao->checkIfIsLent($_POST['selectedEquipmentId']);

if(isset($_POST['responsibleUser']) && checkInput($_POST['initialDate']) && isset($_POST['selectedEquipmentId']) && checkInput($_POST['responsibleUser']) && checkInput($_POST['selectedEquipmentId'])) {
    if(!$isLent) {
        if(checkFullDate($_POST['initialDate'])) { //Se não existir ou se não for válida
            if(!isset($_POST['finalDate'])) {
                $finalDate = null;
            } else {
                $finalDate = $_POST['finalDate'];
            }
            
            $newLent = new lent();
            $newLent->setUser($_POST['responsibleUser']);
            $newLent->setInitialDate($_POST['initialDate']);
            $newLent->setFinalDate($finalDate);
            $newLent->setContact($_POST['contact']);
            $newLent->setObs($_POST['obs']);
            $newLent->setEquipmentId($_POST['selectedEquipmentId']);

            $lentDao->createLent($newLent);

            unset($_SESSION['lentEquipmentError']);
            $internalCode = explode(" - ", $_POST['equipments'])[0];

            $_SESSION['successMessage'] = "O processo de emprestimo do equipamento "  .  $internalCode . " foi criado com sucesso.";
        } else {
            $_SESSION['indexErrorMessage'] = "As datas inseridas não são validas.";
        }
    } else {
        $_SESSION['indexErrorMessage'] = "Este equipamento já está emprestado.";
    }
} else {
    $_SESSION['indexErrorMessage'] = "Não foram inseridos todos os dados necessários.";
}

header('Location: ../index.php');
die(); 