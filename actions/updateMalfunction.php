<?php
require_once '../config.php';
require_once '../dao/malfunctionsDaoMS.php';
require_once '../dao/providersDaoMS.php';
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

if(checkFullDate($_POST['dateMalfunction'])) {
    if(isset($_POST['provider']) && checkInput($_POST['provider'])) {
        $malfunction = new malfunctionsDaoMS($pdo);
        $provider = new providersDaoMS($pdo);

        $newMalfunction = new malfunction();

        $providerId = $provider->getIdByName($_POST['provider']);
        $newMalfunction->setDate($_POST['dateMalfunction']);
        $newMalfunction->setDescription($_POST['description']);
        $newMalfunction->setAssistanceId($assistanceId);
        $newMalfunction->setProviderName(['provider']);
        $newMalfunction->setProviderId($providerId);

        $malfunction->createMalfunction($newMalfunction);

        unset($_SESSION['updateMalfunctionError']);
        $_SESSION['successMessage'] = "O avaria na data " . $_POST['dateMalfunction'] . " foi atualizada com sucesso.";

        header('Location: ../index.php');
        die();
    } else {
        $_SESSION['updateMalfunctionError'] = 'Aparentemente o fornecedor selecionado não é válido.';
    }
} else {
    $_SESSION['updateMalfunctionError'] = 'Aparentemente a data inserida não é válida.';
}

header('Location: ../pages/updateMalfunction.php');
die();
