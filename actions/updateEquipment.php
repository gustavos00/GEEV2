<?php
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/brandsDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/statesDaoMS.php';
require_once '../dao/categorysDaoMS.php';
session_start();

$equipments = new equipmentsDAOMS($pdo);
$brands = new brandsDAOMS($pdo);
$providers = new providersDAOMS($pdo);
$states = new statesDAOMS($pdo);
$categorys = new categorysDAOMS($pdo);

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function checkInput($i) {
    return (trim($i) != "");
}

if(isset($_POST['brand']) && isset($_POST['provider']) && isset($_POST['model']) && isset($_POST['type']) && isset($_POST['model']) && checkInput($_POST['brand']) && checkInput($_POST['provider']) && checkInput($_POST['model']) && checkInput($_POST['type']) && checkInput($_POST['model'])) {
    if (filter_var($_POST['ipAdress'], FILTER_VALIDATE_IP) && isset($_POST['ipAdress'])) {
        $categoryId = $categorys->getIdByName($_POST['type']);
        $providerId = $providers->getIdByName($_POST['provider']);
        $stateId = $states->getIdByName($_POST['state']);
        $brandId = $brands->getIdByName($_POST['brand']);
        $userData = $equipments->equipmentUserData($_POST['id']);

        $updatedEquipment = new equipments();
        
        $updatedEquipment->setId($_POST['id']);
        $updatedEquipment->setInternalCode($_POST['internalCode']);
        $updatedEquipment->setCategoryId($categoryId);
        $updatedEquipment->setCategoryName($_POST['type']);
        $updatedEquipment->setModel($_POST['model']);
        $updatedEquipment->setBrandName($_POST['brand']);
        $updatedEquipment->setSerieNumber($_POST['serieNumber']);
        $updatedEquipment->setFeatures($_POST['features']);
        $updatedEquipment->setObs($_POST['obs']);
        $updatedEquipment->setAcquisitionDate($_POST['acquisitionDate']);
        $updatedEquipment->setPatrimonialCode($_POST['patrimonialCode']);
        $updatedEquipment->setUser($_POST['user']);
        $updatedEquipment->setLocation($_POST['location']);
        $updatedEquipment->setUserDate($_POST['userDate']);
        $updatedEquipment->setLanPort($_POST['lanPort']);
        $updatedEquipment->setActiveEquipment($_POST['activeEquipment']);
        $updatedEquipment->setIpAdress($_POST['ipAdress']);

        $updatedEquipment->setProviderId($providerId);
        $updatedEquipment->setProviderName($_POST['provider']);

        $updatedEquipment->setBrandId($brandId);
        $updatedEquipment->setBrandName($_POST['brand']);

        $updatedEquipment->setStateId($stateId);
        $updatedEquipment->setStateName($_POST['state']);

        $updatedEquipment->setCategoryId($categoryId);
        $updatedEquipment->setCategoryName($_POST['type']);
                
        if($userData->getUser() != $_POST['user'] && !is_null($userData->getUser())) {
            
            $year = explode('/', $_POST['userDate']);
            if($year == "0000") { //Se data for igual a default (0000-00-00)
                $reallyFinalDate = date("Y-m-d");
            } else {
                $reallyFinalDate = $_POST['userDate'];
            }

            $equipments->setHistoric($_POST['user'], $userData->getUserDate(), $reallyFinalDate, $_POST['id']);
        }

        $equipments->updateEquipment($updatedEquipment);

        unset($_SESSION['updateEquipmentError']);
        $_SESSION['successMessage'] = "O equipmento " . $_POST['internalCode'] . " foi criado com sucesso.";
        
        if(isset($_COOKIE['__geeupdateequipment'])) {
            setcookie("__geeupdateequipment", 'DELETED', 1, '/');
        }

   
    } else {
        $_SESSION['updateEquipmentError'] = "O endereço IP inserido não é valido.";
    }
} else {
    $_SESSION['updateEquipmentError'] = "Não foram introduzidos todos os dados necessários.";
}

