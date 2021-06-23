<?php
require_once '../vendor/autoload.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/malfunctionsDaoMS.php';
require_once '../config.php';
session_start();

$pdfData = '';

function generateHeader($headerName, $headerValue) {
    return '<h1>' . $headerName . ' Nº' . $headerValue . '</h1>';
}

function generateSubheader($subheaderName) {
    return '<h2>' . $subheaderName . '</h2>';
}

function generateAssistance($pdo, $data) {
    $malfunction = new malfunctionsDAOMS($pdo);
    $allMalfunctions = $malfunction->getAll();
    $data = '';

    foreach($allMalfunctions as $malfunctionData) {
        $data .= generateHeader($data, 'Assistência', $malfunctionData->getId());
        $data .= '<p><strong>Data: </strong>' . $malfunctionData->getDate() . '</p>';
        $data .= '<p><strong>Status: </strong>' . $malfunctionData->getStatus() . '</p>';
        $data .= '<p><strong>Descrição: </strong>' . $malfunctionData->getDescription() . '</p>';
        $data .= '<p><strong>Responsável: </strong>' . $malfunctionData->getProviderName() . '</p>';  
        $data .= '<br>';
    }
    return $data;
}

function generateEquipment($pdo) {
    $equipment = new equipmentsDAOMS($pdo);
    $allEquipments = $equipment->getAll();
    $data = '';

    foreach($allEquipments as $equipmentData) {
        $data .= generateHeader('Equipamento', $equipmentData->getId());
        $data .= generateSubheader('Descrição');
        $data .= '<p><strong>Código Interno: </strong>' . $equipmentData->getInternalCode() . '</p>';
        $data .= '<p><strong>Tipo: </strong>' . $equipmentData->getCategoryName() . '</p>';  
        $data .= '<p><strong>Estado: </strong>' . $equipmentData->getStateName() . '</p>';  
        $data .= '<p><strong>Marca: </strong>' . $equipmentData->getBrandName() . '</p>';  
        $data .= '<p><strong>Modelo: </strong>' . $equipmentData->getModel() . '</p>'; 
        $data .= '<p><strong>Número de Série: </strong>' . $equipmentData->getSerieNumber() . '</p>';
        $data .= '<p><strong>Fornecedor: </strong>' . $equipmentData->getProviderName() . '</p>';

        $data .= generateSubheader('Caracterização');
        $data .= '<p><strong>Características: </strong>' . $equipmentData->getFeatures() . '</p>';
        $data .= '<p><strong>Observações: </strong>' . $equipmentData->getObs() . '</p>';
        $data .= '<p><strong>Data de aquisição: </strong>' . $equipmentData->getAcquisitionDate() . '</p>';
        $data .= '<p><strong>Código de Património: </strong>' . $equipmentData->getPatrimonialCode() . '</p>';

        $data .= generateSubheader('Localização');
        $data .= '<p><strong>Utilizador: </strong>' . $equipmentData->getUser() . '</p>';
        $data .= '<p><strong>Localização: </strong>' . $equipmentData->getLocation() . '</p>';
        $data .= '<p><strong>Data de utilizador: </strong>' . $equipmentData->getUserDate() . '</p>';

        $data .= generateSubheader('Informação de rede');
        $data .= '<p><strong>Porta de rede: </strong>' . $equipmentData->getLanPort() . '</p>';
        $data .= '<p><strong>Equipamento ativo: </strong>' . $equipmentData->getActiveEquipment() . '</p>';
        $data .= '<p><strong>Endereço IP </strong>' . $equipmentData->getIpAdress() . '</p>';
        $data .= '<br>';
    }
    return $data;
}

function generateMalfuncion($pdo) {
    $malfunctions = new malfunctionsDAOMS($pdo);
    $allMalfunctions = $malfunctions->getAll();
    $data = '';

    foreach($allMalfunctions as $malfunctionData) {
        $providerName = $malfunctionData->getProviderName();

        $data .= generateHeader('Avaria', $malfunctionData->getId());
        $data .= '<p><strong>Data avaria: </strong>' . $malfunctionData->getDate() . '</p>';
        $data .= '<p><strong>Descrição: </strong>' . $malfunctionData->getDescription() . '</p>';
        $data .= '<p><strong>Estado: </strong>' . $malfunctionData->getStatus() . '</p>';

        if (trim($providerName) !== "") {
            $data .= '<p><strong>Reparado por: </strong>' . $providerName . '</p>';
        }
        
    }
    return $data;
}


function generateSoftware($pdo) {
    $softwares = new softwaresDAOMS($pdo);
    $allSoftwares = $softwares->getAllSoftwares();
    $data = '';

    foreach($allSoftwares as $softwareData) {
        $providerName = $softwareData->getProviderName();

        $data .= generateHeader('Software', $softwareData->getId());
        $data .= '<p><strong>Tipo: </strong>' . $softwareData->getTypeName() . '</p>';
        $data .= '<p><strong>Versão: </strong>' . $softwareData->getVersion() . '</p>';
        $data .= '<p><strong>Chave: </strong>' . $softwareData->getKey() . '</p>';
        $data .= '<p><strong>Data inicial de contrato: </strong>' . $softwareData->getInitialDate() . '</p>';
        $data .= '<p><strong>Data final de contrato: </strong>' . $softwareData->getFinalDate() . '</p>';

        if (trim($providerName) !== "") {
            $data .= '<p><strong>Fornecedor: </strong>' . $providerName . '</p>';
        }
    }
    return $data;
}

switch ($_POST['category']) {
    case 'Assistências':
        echo ' <title> Relatorio assistências - GEE</title>';

        $pdfData = generateAssistance($pdo);
        break;

    case 'Equipamentos':
        echo ' <title> Relatorio equipamentos - GEE</title>';

        $pdfData = generateEquipment($pdo);
        break;

    case 'Avarias':
        echo ' <title> Relatorio avarias - GEE</title>';

        $pdfData = generateMalfuncion($pdo);
        break; 

    case 'Softwares':
        echo ' <title> Relatorio avarias - GEE</title>';

        $pdfData = generateSoftware($pdo);
        break; 

    case 'Emprestimos':
        echo ' <title> Relatorio emprestimos - GEE</title>';

        $pdfData = generateSoftware($pdo);
        break; 
    default:
        $_SESSION['generatePDFError'] = "Aparentemente ocorreu um problema ao determinar que tipo de PDF você quer gerar. Tente novamemente";


        break;
}

$date = date("Y-m-d");
$pdfData .= '<p> Gerado na data: ' . $date . '</p>';  

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfData);
