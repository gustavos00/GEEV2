<?php
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/brandsDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/statesDaoMS.php';
require_once '../dao/categorysDaoMS.php';
session_start();

$equipments = new equipmentsDAOMS($pdo);
$brands = new brandsDAOMS($pdo);
$providers = new providersDAOMS($pdo);
$softwares = new softwaresDAOMS($pdo);
$states = new statesDAOMS($pdo);
$categorys = new categorysDAOMS($pdo);

//Get data
$data = json_decode(file_get_contents("php://input"));
$categoryId = $categorys->getIdByName($data->category);
$providerId = $providers->getIdByName($data->provider);
$stateId = $states->getIdByName($data->state);
$brandId = $brands->getIdByName($data->brand);

$softwaresData = $data->softwares;

function checkInput($i) {
    return (trim($i) != "");
}

if(!isset($data->dataAdquisicao)) {
    $date = date("Y-m-d");
} else {
    $date = $data->dataAdquisicao;
}

if($data->userDate == "") {
    $data->userDate = null;
}

if(checkInput($data->internalCode)) { //Check if input is just empty spaces
    if(isset($data->brand) && isset($data->model) && isset($data->category)) { //Check if exist some important data
        if(checkInput($data->ipAdress)) {
            if(!filter_var($data->ipAdress, FILTER_VALIDATE_IP)) {
                $_SESSION['updateEquipmentError'] = "O endereço IP inserido não é valido.";
            } 

            if(!$equipments->getIpStatus($data->ipAdress)) {
                $_SESSION['updateEquipmentError'] = "O endereço IP inserido já está a ser utilizado.";
            }

            header('Location: ../index.php');
            die();
        } 

        if ($equipments->getInternalCodeStatus($data->internalCode)) { //Validate equipment
            $newEquipment = new equipments();   
            
            $newEquipment->setInternalCode($data->internalCode);
            $newEquipment->setModel($data->model);
            $newEquipment->setSerieNumber($data->serieNumber);
            $newEquipment->setFeatures($data->features);
            $newEquipment->setObs($data->obs);
            $newEquipment->setAcquisitionDate($date);
            $newEquipment->setPatrimonialCode($data->patrimonialCode);
            $newEquipment->setUser($data->user);
            $newEquipment->setLocation($data->location);
            $newEquipment->setUserDate($data->userDate);
            $newEquipment->setLanPort($data->lanPort);
            $newEquipment->setActiveEquipment($data->activeEquipment);
            $newEquipment->setIpAdress($data->ipAdress);

            $newEquipment->setProviderId($providerId);
            $newEquipment->setProviderName($data->provider);

            $newEquipment->setBrandId($brandId);
            $newEquipment->setBrandName($data->brand);

            $newEquipment->setStateId($stateId);
            $newEquipment->setStateName($data->state);

            $newEquipment->setCategoryId($categoryId);
            $newEquipment->setCategoryName($data->category);

            foreach ($softwaresData as $software) {
                $equipments->linkSoftwares($software->id, 19);
            }

            $equipments->createEquipment($newEquipment);

            unset($_SESSION['createEquipmentError']);
            $_SESSION['successMessage'] = "O equipmento " . $data->internalCode . " foi criado com sucesso.";
            
            if(isset($_COOKIE['__geecreateequipment'])) {
                setcookie("__geecreateequipment", 'DELETED', 1, '/');
            } 

            http_response_code(200);

        } else {
            $_SESSION['createEquipmentError'] = "Já existe um equipamento com esse código interno."; 
        }
    } else {
        $_SESSION['createEquipmentError'] = "Não foram introduzidos todos os dados necessários.";
    }
} else {
    $_SESSION['createEquipmentError'] = "Algum dos dados inseridos não é valido.";
}

$_SESSION['createEquipmentError'] = "Algum dos dados inseridos não é valido.";

print_r($_SESSION['createEquipmentError']);
http_response_code(400);

