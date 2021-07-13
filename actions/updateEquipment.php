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

function checkInput($i) {
    return (trim($i) != "");
}

if(checkInput($data->internalCode)) { //Check if input is just empty spaces
    if(isset($data->brand) && isset($data->model) && isset($data->category)) { //Check if exist some important data
        if(checkInput($data->ipAdress)) {
            if(!filter_var($data->ipAdress, FILTER_VALIDATE_IP)) {
                $_SESSION['updateEquipmentError'] = "O endereço IP inserido não é valido.";
                
                header('Location: ../index.php');
                die();
            } 
        } 

        $equipmentStatus = $equipments->getEquipmentStatus($data->ipAdress, $data->internalCode, $data->serieNumber);

        if (!$equipmentStatus) { //Validate equipment
            $updatedEquipment = new equipments();
            
            $updatedEquipment->setId($data->id);
            $updatedEquipment->setInternalCode($data->internalCode);
            $updatedEquipment->setCategoryId($categoryId);
            $updatedEquipment->setCategoryName($data->type);
            $updatedEquipment->setModel($data->model);
            $updatedEquipment->setBrandName($data->brand);
            $updatedEquipment->setSerieNumber($data->serieNumber);
            $updatedEquipment->setFeatures($data->features);
            $updatedEquipment->setObs($data->obs);
            $updatedEquipment->setAcquisitionDate($data->acquisitionDate);
            $updatedEquipment->setPatrimonialCode($data->patrimonialCode);
            $updatedEquipment->setUser($data->user);
            $updatedEquipment->setLocation($data->location);
            $updatedEquipment->setUserDate($data->userDate);
            $updatedEquipment->setLanPort($data->lanPort);
            $updatedEquipment->setActiveEquipment($data->activeEquipment);
            $updatedEquipment->setIpAdress($data->ipAdress);

            $updatedEquipment->setProviderId($providerId);
            $updatedEquipment->setProviderName($data->provider);

            $updatedEquipment->setBrandId($brandId);
            $updatedEquipment->setBrandName($data->brand);

            $updatedEquipment->setStateId($stateId);
            $updatedEquipment->setStateName($data->state);

            $updatedEquipment->setCategoryId($categoryId);
            $updatedEquipment->setCategoryName($data->type);


            $equipments->updateEquipment($updatedEquipment);

            unset($_SESSION['updateEquipmentError']);
            $_SESSION['successMessage'] = "O equipmento " . $_POST['internalCode'] . " foi atualizado com sucesso.";
            
            if(isset($_COOKIE['__geeupdateequipment'])) {
                setcookie("__geeupdateequipment", 'DELETED', 1, '/');
            }
            echo 'SUCESSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS';

            header('Location: ../index.php');
            die();
        } else {
            $_SESSION['updateEquipmentError'] = "Já existe um equipamento com esse endereço IP."; 
        }
    } else {
        $_SESSION['updateEquipmentError'] = "Não foram introduzidos todos os dados necessários.";
    }
} else {
    $_SESSION['updateEquipmentError'] = "Algum dos dados inseridos não é valido.";
}

header('Location: ../pages/updateEquipment.php?id=' .  $_POST['id']);
die();

echo 'FAILEDDDDDDDDDDDDDDDDDDDDDD';