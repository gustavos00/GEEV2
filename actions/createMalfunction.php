<?php
require_once '../config.php';
require_once '../dao/malfunctionsDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/equipmentsDaoMS.php';
session_start();

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

function checkInput($i) {
    return (trim($i) != "");
}

$assistanceId = null;

if(isset($_POST['assistance'])) {
    $assistanceId = $_POST['assistanceId'];
}

if(checkFullDate($_POST['dateMalfunction'])) {//Check if date is valid
    if(isset($_POST['provider']) && checkInput($_POST['provider'])) {
        $malfunction = new malfunctionsDaoMS($pdo);
        $provider = new providersDaoMS($pdo);
        $equipments = new equipmentsDaoMS($pdo);

        $eqId = $equipments->getIdByInternalCode(explode(" - ", $_POST['equipments'])[0]);

        $newMalfunction = new malfunction();

        $providerId = $provider->getIdByName($_POST['provider']);
        $newMalfunction->setDate($_POST['dateMalfunction']);
        $newMalfunction->setDescription($_POST['description']);
        $newMalfunction->setAssistanceId($assistanceId);
        $newMalfunction->setProviderName(['provider']);
        $newMalfunction->setProviderId($providerId);

        $malfunctionId = $malfunction->createMalfunction($newMalfunction);
        $equipments->setMalfunction($eqId, $malfunctionId);

        unset($_SESSION['createMalfunctionError']);
        $_SESSION['successMessage'] = "O avaria na data " . $_POST['dateMalfunction'] . " foi criada com sucesso.";

        header('Location: ../index.php');
        die();
    } else {
        $_SESSION['createMalfunctionError'] = 'Aparentemente o fornecedor selecionado não é válido.';
    }
} else {
    $_SESSION['createMalfunctionError'] = 'Aparentemente a data inserida não é válida.';
}
