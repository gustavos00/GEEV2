<?php
require_once '../config.php';
require_once '../dao/lentDaoMS.php';
require_once '../dao/statesDaoMS.php';
session_start();

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

$lentDao = new lentDAOMS($pdo);
$statesDaoMS = new statesDAOMS($pdo);
$isLent = $lentDao->checkIfIsLent($_POST['selectedEquipmentId']);

if($isLent) {
    if(checkFullDate($_POST['finalDate'])) {
        $lent = new lent();
        $lent->setFinalDate($_POST['finalDate']);
        $lent->setEquipmentId($_POST['selectedEquipmentId']);

        $stateId = $statesDaoMS->getIdByName('Ativo');
        if(is_null($stateId)) {
            $stateId = $statesDaoMS->createActiveState();
            $_SESSION['successMessage'] = "O equipamento foi retornado com sucesso mas como não havia um estado 'Ativo' , foi criado.";
        } else {
            $_SESSION['successMessage'] = "O equipamento foi retornado com sucesso.";
        }

        unset($_SESSION['indexErrorMessage']);
        $lentDao->returnEquipment($lent, $stateId);
    } else {
        $_SESSION['indexErrorMessage'] = "A data inserida não é valida.";
    }
} else {
    $_SESSION['indexErrorMessage'] = "O equipamento selecionado não está emprestado.";
}

header('Location: ../index.php');
die();
