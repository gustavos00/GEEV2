<?php
require '../config.php';
require '../dao/assistanceDaoMS.php';
require '../dao/equipmentsDaoMS.php';
require '../dao/providersDaoMS.php';
session_start();

function validateDate($date, $format = 'Y-m-dH:i')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function checkInput($i) {
    return (trim($i) != "");
}

if (checkInput($_POST['technical']) && checkInput($_POST['objective'])) {
    $assistance = new assistanceDAOMS($pdo);
    $equipments = new equipmentsDAOMS($pdo);
    $providers = new providersDAOMS($pdo);
    $newAssistance = new assistance();

    $equipmentId = $equipments->getIdByInternalCode(explode(' (', $_POST['equipments'])[0]);
    $typeId = $assistance->getAssistanceTypeIdBYName($_POST['assistanceType']);
    $technicalId = $providers->getIdByName($_POST['technical']);

    $initialDateAssistance = null;
    $frontOffice = null;

    //DATA INICIAL
    if(!isset($_POST['initialDateAssistance']) || !validateDate($_POST['initialDateAssistance'])) { //Se não existir ou se não for valida
        $initialDateAssistance = date("Y-m-d H:i:s"); 
    } else {
        $initialDateAssistance = $_POST['initialDateAssistance'];
    }

    //DATA INICIAL
    if(!isset($_POST['finalDateAssistance']) || !validateDate($_POST['finalDateAssistance'])) { //Se não existir ou se não for valida
        $finalDateAssistance = date("Y-m-d H:i:s"); 
    } else {
        $finalDateAssistance = $_POST['finalDateAssistance'];
    }

    //FrontOffice
    if(isset($_POST['frontOffice'])) {
        $frontOffice = "Sim";
    } else {
        $frontOffice = "Não";
    }

    $newAssistance->setInitialDate($initialDateAssistance);
    $newAssistance->setFinalDate($finalDateAssistance);
    $newAssistance->setDescription($_POST['description']);
    $newAssistance->setGoals($_POST['objective']);
    $newAssistance->setFrontOffice($frontOffice);

    $newAssistance->setTypeId($typeId);
    $newAssistance->setTypeName($_POST['assistanceType']);

    $newAssistance->setTechnicalId($technicalId);
    $newAssistance->setTechnicalName($_POST['technical']);

    $newAssistance->setEquipmentId($equipmentId);
    $newAssistance->setEquipmentName(explode(' (', $_POST['equipments'])[0]);

    $assistance->updateAssistance($newAssistance);

    $_SESSION['successMessage'] = "A assistência na data " . $initialDateAssistance . " foi atualizada com sucesso.";

    header('Location: ../index.php');
    exit(0);
}

$_SESSION['createAssistanceError'] = 'Aparentemente não foram inseridos todos os dados necessários.';
header('Location: ../pages/createAssistance.php');
exit(0);


