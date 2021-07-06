<?php
require '../config.php';
require '../dao/assistanceDaoMS.php';
require '../dao/equipmentsDaoMS.php';
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
    $newAssistance = new assistance();

    $equipmentId = $equipments->getIdByInternalCode(explode(' - ', $_POST['equipments'])[0]);

    $typeId = $assistance->getAssistanceTypeIdBYName($_POST['assistanceType']);

    $initialDateAssistance = null;
    $frontOffice = null;

    //DATA INICIAL
    if(!isset($_POST['initialDateAssistance'])) { //Se não for valida «
        $_SESSION['createAssistanceError'] = "A data inicial inserida não é válida.";
    } else {
        $initialDateAssistance = date("Y-m-d H:i:s"); 
    }

    //DATA FINAL
    if($_POST['finallDateAssistance'] !== "" && !validateDate($_POST['finallDateAssistance'])) { //Existir mas não for valida
        $_SESSION['createAssistanceError'] = "A data final inserida não é válida.";
    }

    //FrontOffice
    if(isset($_POST['frontOffice'])) {
        $frontOffice = "Sim";
    } else {
        $frontOffice = "Não";
    }

    $newAssistance->setInitialDate($initialDateAssistance);
    $newAssistance->setFinalDate($_POST['finallDateAssistance']);
    $newAssistance->setDescription($_POST['description']);
    $newAssistance->setTechnical($_POST['technical']);
    $newAssistance->setGoals($_POST['objective']);
    $newAssistance->setFrontOffice($frontOffice);
    $newAssistance->setTypeId($typeId);
    $newAssistance->setEquipmentId($equipmentId);

    $assistance->createAssistance($newAssistance);

    $_SESSION['successMessage'] = "A assistência na data " . $initialDateAssistance . " foi criada com sucesso.";
    
    header('Location: ../index.php');
    exit(0);
}

$_SESSION['createAssistanceError'] = 'Aparentemente não foram inseridos todos os dados necessários.';
header('Location: ../pages/createAssistance.php');
exit(0);