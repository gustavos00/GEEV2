<?php
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/brandsDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/statesDaoMS.php';
require_once '../dao/categorysDaoMS.php';
session_start();

$equipments = new equipmentsDAOMS($pdo);
$brands = new brandsDAOMS($pdo);
$softwaresDAOMS = new softwaresDAOMS($pdo);
$providers = new providersDAOMS($pdo);
$states = new statesDAOMS($pdo);
$categorys = new categorysDAOMS($pdo);

//Get data
$data = json_decode(file_get_contents("php://input"));
$categoryId = $categorys->getIdByName($data->category);
$providerId = $providers->getIdByName($data->provider);
$stateId = $states->getIdByName($data->state);
$brandId = $brands->getIdByName($data->brand);
$oldEquipmentData = $equipments->getSpecificById($data->id);

$softwaresData = $data->softwares;
$ipStatus = 'same';
$serieNumberStatus = 'same';
$internalCodeStatus = 'same';

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

if($data->ipAdress == "") {
    $data->ipAdress = null;
}

if($data->serieNumber == "") {
    $data->serieNumber = null;
}

if(checkInput($data->internalCode)) { //Check if input is just empty spaces
    if(isset($data->brand) && isset($data->model) && isset($data->category) && isset($data->provider)) { //Check if exist some important data
        $ipStatus = 'd';
        if($oldEquipmentData->getId() != $data->id) {
            if(!is_null($data->ipAdress)) {
                if(filter_var($data->ipAdress, FILTER_VALIDATE_IP)) {
                    if($equipments->getIpStatus($data->ipAdress)) {
                        print_r("O endereço IP inserido já está a ser utilizado.");
                        
                        http_response_code(400);
                        return false;
                    }
                } else {
                    print_r("O endereço IP inserido não é valido.");
        
                    http_response_code(400);
                    return false;
                }
            }
        }

        if($oldEquipmentData->getSerieNumber() != $data->serieNumber) {
            $serieNumberStatus = 'd';
            if(!is_null($data->serieNumber)) {
                if(filter_var($data->serieNumber, FILTER_SANITIZE_STRING)) {
                    if($equipments->getSerieNumberStatus($data->serieNumber)) {
                        print_r("O número de série inserido já está a ser utilizado.");
    
                        http_response_code(400);
                        return false;
                    } 
                } else {
                    print_r("O número de série inserido não é valido.");
    
                    http_response_code(400);
                    return false;
                }       
            }    
        }

        if($oldEquipmentData->getInternalCode() != $data->internalCode) {
            $internalCodeStatus = 'd';
        }

        //Se o novo for igual ao antigo retorna true, se for diferente retorna false    
        if (!$equipments->getInternalCodeStatus($data->internalCode)) { //Validate equipment
            $updatedEquipment = new equipments();
            
            $updatedEquipment->setId($data->id);
            $updatedEquipment->setInternalCode($data->internalCode);
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
            $updatedEquipment->setCategoryName($data->category);

            $equipments->updateEquipment($updatedEquipment, $internalCodeStatus, $serieNumberStatus, $ipStatus);
            
            
            if ($data->status == "d") {
                $softwaresIds = [];
                $softwaresData = $softwaresDAOMS->getSpecificEquipmentSoftwares($data->id);
            
                foreach ($softwaresData as $software) {
                    array_push($softwaresIds, $software->getId());
                }
            
                $softwaresDAOMS->unlinkSoftwares($data->id, $softwaresIds);
                $softwaresIds = [];
            
                foreach ($data->softwares as $software) {
                    $softwaresIds[] = $software->id;
                }

                $softwaresDAOMS->linkSoftwares($data->id, $softwaresIds);
            }   

            unset($_SESSION['updateEquipmentError']);
            $_SESSION['successMessage'] = "O equipmento " . $data->internalCode . " foi atualizado com sucesso.";
            
            if(isset($_COOKIE['__geeupdateequipment'])) {
                setcookie("__geeupdateequipment", 'DELETED', 1, '/');
            }

            http_response_code(200);
        } else {
            $_SESSION['updateEquipmentError'] = "Já existe um equipamento com esse código interno  ."; 
            print_r($_SESSION['updateEquipmentError']);
            http_response_code(400);
        }
    } else {
        $_SESSION['updateEquipmentError'] = "Não foram introduzidos todos os dados necessários.";
        print_r($_SESSION['updateEquipmentError']);
        http_response_code(400);
    }
} else {
    $_SESSION['updateEquipmentError'] = "Algum dos dados inseridos não é valido.";
    print_r($_SESSION['updateEquipmentError']);
    http_response_code(400);
}


