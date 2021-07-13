<?php
require_once '../config.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/providersDaoMS.php';
session_start();

function checkFullDate($date) {
    $dateArray = explode("-", $date);
    return checkdate($dateArray[1], $dateArray[2], $dateArray[0]);
}

function checkInput($i) {
    return (trim($i) != "");
}

$softwares = new softwaresDAOMS($pdo);
$providers = new providersDAOMS($pdo);

if(checkFullDate($_POST['initialDate']) && checkInput($_POST['key']) && checkInput($_POST['version'])) {
    if(isset($_POST['type']) && isset($_POST['provider']) && checkInput($_POST['type']) && checkInput($_POST['provider'])) {
        $typeId = $softwares->getSoftwareTypeIdByName($_POST['type']);
        $providerId = $providers->getIdByName($_POST['provider']);
        
        if(filter_var($typeId, FILTER_VALIDATE_INT) && filter_var($providerId, FILTER_VALIDATE_INT)) {
            $newSoftware = new softwares();
        
            $newSoftware->setId($_POST['id']);
            $newSoftware->setKey($_POST['key']);
            $newSoftware->setVersion($_POST['version']);
            $newSoftware->setInitialDate($_POST['initialDate']);
            $newSoftware->setFinalDate($_POST['finalDate']);
        
            $newSoftware->setTypeName($_POST['type']);
            $newSoftware->setTypeId($typeId);
        
            $newSoftware->setProviderName($_POST['provider']);
            $newSoftware->setProviderId($providerId);

            $softwares->updateSoftware($newSoftware);

            unset($_SESSION['updateSoftwareError']);
            
            if(isset($_COOKIE['__geeupdatesoftware'])) {
                setcookie("__geeupdatesoftware", 'DELETED', 1, '/');
            }

            $_SESSION['successMessage'] = "O software " . $_POST['type'] . " (" . $_POST['version'] . ") foi atualizado com sucesso.";

            header('Location: ../index.php');
            die();
        } else {
            $_SESSION['updateSoftwareError'] = 'Ocorreu um erro a encontrar o tipo e/ou fornecedor selecionado.';
        }
    } else {
        $_SESSION['updateSoftwareError'] = 'Aparentemente não inseriu todos os dados necessário (Tipo e fornecedor).';
    }
} else {
    $_SESSION['updateSoftwareError'] = 'As datas inseridas não são validas.';
} 

header('Location: ../pages/updateSoftware.php?id=' . $_POST['id']);
die();
