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

$categoryId = $categorys->getIdByName($_POST['category']);
$providerId = $providers->getIdByName($_POST['provider']);
$stateId = $states->getIdByName($_POST['state']);
$brandId = $brands->getIdByName($_POST['brand']);

if(!isset($_POST['dataAdquisicao'])) {
    $date = date("Y-m-d");
} else {
    $date = $_POST['dataAdquisicao'];
}

if($_POST['userDate'] == "") {
    $_POST['userDate'] = null;
}

if(isset($_POST['brand']) && isset($_POST['model']) && isset($_POST['category']) && isset($_POST['model'])) {
    if (filter_var($_POST['ipAdress'], FILTER_VALIDATE_IP) && isset($_POST['ipAdress'])) {
        $newEquipment = new equipments();
        
        $newEquipment->setInternalCode($_POST['internalCode']);
        $newEquipment->setModel($_POST['model']);
        $newEquipment->setSerieNumber($_POST['serieNumber']);
        $newEquipment->setFeatures($_POST['features']);
        $newEquipment->setObs($_POST['obs']);
        $newEquipment->setAcquisitionDate($date);
        $newEquipment->setPatrimonialCode($_POST['patrimonialCode']);
        $newEquipment->setUser($_POST['user']);
        $newEquipment->setLocation($_POST['location']);
        $newEquipment->setUserDate($_POST['userDate']);
        $newEquipment->setLanPort($_POST['lanPort']);
        $newEquipment->setActiveEquipment($_POST['activeEquipment']);
        $newEquipment->setIpAdress($_POST['ipAdress']);

        $newEquipment->setProviderId($providerId);
        $newEquipment->setProviderName($_POST['provider']);

        $newEquipment->setBrandId($brandId);
        $newEquipment->setBrandName($_POST['brand']);

        $newEquipment->setStateId($stateId);
        $newEquipment->setStateName($_POST['state']);

        $newEquipment->setCategoryId($categoryId);
        $newEquipment->setCategoryName($_POST['category']);

        $equipments->createEquipment($newEquipment);

        unset($_SESSION['createEquipmentError']);
        $_SESSION['createEquipmentSuccess'] = "O equipmento " . $_POST['internalCode'] . " foi criado com sucesso.";
        
        if(isset($_COOKIE['__geecreateequipment'])) {
            setcookie("__geecreateequipment", 'DELETED', 1, '/');
        }

        header('Location: ../index.php');
        die();

    } else {
        $_SESSION['createEquipmentError'] = "O endereço IP inserido não é valido.";
    }
} else {
    $_SESSION['createEquipmentError'] = "Não foram introduzidos todos os dados necessários.";
}

header('Location: ../pages/createEquipment.php');
die();