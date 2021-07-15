<?php
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/lentDaoMS.php';
require_once '../dao/statesDaoMS.php';
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
$equipmentsDaoMS = new equipmentsDAOMS($pdo);
$statesDaoMS = new statesDAOMS($pdo);
$isLent = $lentDao->checkIfIsLent($_POST['selectedEquipmentId']);

if(isset($_POST['responsibleUser']) && checkInput($_POST['initialDate']) && isset($_POST['selectedEquipmentId']) && checkInput($_POST['responsibleUser']) && checkInput($_POST['selectedEquipmentId'])) {
    if(!$isLent) {
        if(checkFullDate($_POST['initialDate'])) { //Se não existir ou se não for válida
            $internalCode = explode(" - ", $_POST['equipments'])[0];
            if($_POST['finalDate'] == "") {
                $finalDate = null;

                $stateId = $statesDaoMS->getIdByName('Emprestado');
                if(is_null($stateId)) {
                    $stateId = $statesDaoMS->createLendState();
                    $_SESSION['successMessage'] = "O processo foi criado mas como não havia um estado 'Abatido', foi criado.";
                } else {
                    unset($_SESSION['indexErrorMessage']);

                    $_SESSION['successMessage'] = "O processo de emprestimo do equipamento "  .  $internalCode . " foi criado com sucesso.";
                }
            } else {
                $finalDate = $_POST['finalDate'];

                $stateId = $statesDaoMS->getIdByName('Ativo');//Recebe o id do ativo
                if(is_null($stateId)) { //Se for null
                    $stateId = $statesDaoMS->createActiveState();//Cria o ativo
                    
                    $_SESSION['successMessage'] = "O equipamento " . $internalCode . " foi emprestado e retornado com sucesso mas como não havia um estado 'Ativo' , foi criado.";
                } else {
                    $_SESSION['successMessage'] = "O equipamento " . $internalCode . " foi emprestado e retornado com sucesso.";
                }
            }

            $newLent = new lent();
            $newLent->setUser($_POST['responsibleUser']);
            $newLent->setInitialDate($_POST['initialDate']);
            $newLent->setFinalDate($finalDate);
            $newLent->setContact($_POST['contact']);
            $newLent->setObs($_POST['obs']);
            $newLent->setEquipmentId($_POST['selectedEquipmentId']);

        
            $lentDao->createLent($newLent);
            $status = $equipmentsDaoMS->setAsLent($_POST['selectedEquipmentId'], $stateId);    

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