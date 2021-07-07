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

var_dump($_POST);

if (checkInput($_POST['technical']) && checkInput($_POST['objective']) && isset($_POST['assistanceType'])) {
    $assistance = new assistanceDAOMS($pdo);
    $equipments = new equipmentsDAOMS($pdo);
    $providers = new providersDAOMS($pdo);
    $newAssistance = new assistance();

    $equipmentId = $equipments->getIdByInternalCode(explode(' (', $_POST['equipments'])[0]);
    $providerId = $providers->getIdByName($_POST['technical']);
    $typeId = $assistance->getAssistanceTypeIdBYName($_POST['assistanceType']);

    echo 'aaaaaaaaaaaa ' . $_POST['assistanceType'];

    $initialDateAssistance = null;
    $frontOffice = null;

    //DATA INICIAL
    if(validateDate($_POST['initialDateAssistance'])) { //Se não for valida «
        $_SESSION['createAssistanceError'] = "A data inicial inserida não é válida.";
    } else if(!isset($_POST['initialDateAssistance'])) { //Se não existir
        $initialDateAssistance = date("Y-m-d H:i:s"); 
    } else {
        $initialDateAssistance = $_POST['initialDateAssistance'];
    }

    //DATA FINAL
    if(validateDate($_POST['finalDateAssistance'])) { //Se não for valida 
        $_SESSION['createAssistanceError'] = "A data final inserida não é válida.";
    } else if(!isset($_POST['finalDateAssistance'])) { //Se não existir
        $finalDateAssistance = date("Y-m-d H:i:s"); 
    } else {
        $finalDateAssistance = $_POST['finalDateAssistance'];
    }

    $duration = round((strtotime($finalDateAssistance) - strtotime($initialDateAssistance))/3600, 1);

    //FrontOffice
    if(isset($_POST['frontOffice'])) {
        $frontOffice = "Sim";
    } else {
        $frontOffice = "Não";
    }

    $newAssistance->setInitialDate($initialDateAssistance);
    $newAssistance->setFinalDate($finalDateAssistance);
    $newAssistance->setDuration($duration);
    $newAssistance->setDescription($_POST['description']);
    $newAssistance->setTechnicalId($providerId);
    $newAssistance->setGoals($_POST['objective']);
    $newAssistance->setFrontOffice($frontOffice);
    $newAssistance->setTypeId($typeId);
    $newAssistance->setEquipmentId($equipmentId);

    $assistance->createAssistance($newAssistance);

    $_SESSION['successMessage'] = "A assistência na data " . $initialDateAssistance . " foi criada com sucesso.";
    
    header('Location: ../index.php');
    exit(0);
} else {

    $_SESSION['createAssistanceError'] = 'Aparentemente não foram inseridos todos os dados necessários.';
    var_dump($_SESSION);
}
