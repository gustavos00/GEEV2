<?php
require_once '../config.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/providersDaoMS.php';
session_start();

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

$softwares = new softwaresDAOMS($pdo);
$providers = new providersDAOMS($pdo);

if(checkFullDate($_POST['initialDate']) && checkFullDate($_POST['finalDate'])) {
    if(isset($_POST['type']) && isset($_POST['provider'])) {
        $typeId = $softwares->getSoftwareTypeIdByName($_POST['type']);
        $providerId = $providers->getIdByName($_POST['provider']);
        
        if(filter_var($typeId, FILTER_VALIDATE_INT) && filter_var($providerId, FILTER_VALIDATE_INT)) {
            $newSoftware = new softwares();
        
            $newSoftware->setKey($_POST['key']);
            $newSoftware->setVersion($_POST['version']);
            $newSoftware->setInitialDate($_POST['initialDate']);
            $newSoftware->setFinalDate($_POST['finalDate']);
        
            $newSoftware->setTypeName($_POST['type']);
            $newSoftware->setTypeId($typeId);
        
            $newSoftware->setProviderName($_POST['provider']);
            $newSoftware->setProviderId($providerId);

            $softwares->insertSoftware($newSoftware);

            if(isset($_SESSION['createSoftwareError'])) {
                unset($_SESSION['createSoftwareError']);
            }

            header('Location: ../index.php');
            die();

        } else {
            $_SESSION['createSoftwareError'] = 'Ocorreu um erro a encontrar o tipo e/ou fornecedor selecionado.';
        }
    } else {
        $_SESSION['createSoftwareError'] = 'Aparentemente não inseriu todos os dados necessário (Tipo e fornecedor).';
    }
} else {
    $_SESSION['createSoftwareError'] = 'As datas inseridas não são validas.';
}

header('Location: ../pages/createEquipment.php');
die();