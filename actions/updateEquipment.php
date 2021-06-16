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

$categoryId = $categorys->getIdByName($_POST['type']);
$providerId = $providers->getIdByName($_POST['provider']);
$stateId = $states->getIdByName($_POST['state']);
$brandId = $brands->getIdByName($_POST['brand']);

if(isset($_POST['brand']) && isset($_POST['model']) && isset($_POST['type']) && isset($_POST['model'])) {
    if (filter_var($_POST['ipAdress'], FILTER_VALIDATE_IP) && isset($_POST['ipAdress'])) {
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


        $equipments->updateEquipment($updatedEquipment);

        unset($_SESSION['updateEquipmentError']);
        $_SESSION['successMessage'] = "O equipmento " . $_POST['internalCode'] . " foi criado com sucesso.";
        
        if(isset($_COOKIE['__geeupdateequipment'])) {
            setcookie("__geeupdateequipment", 'DELETED', 1, '/');
        }

        header('Location: ../index.php');
        die();
    } else {
        $_SESSION['updateEquipmentError'] = "O endereço IP inserido não é valido.";
    }
} else {
    $_SESSION['updateEquipmentError'] = "Não foram introduzidos todos os dados necessários.";
}

header('Location: ../pages/updateEquipment.php?id=' . $_POST['id']);
die();