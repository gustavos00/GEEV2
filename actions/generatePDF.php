<?php
require_once '../vendor/autoload.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/assistanceDaoMS.php';
require_once '../dao/lentDaoMS.php';
require_once '../dao/statesDaoMS.php';
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

function generateAssistance($pdo) {
    $assistance = new assistanceDAOMS($pdo);
    switch ($_POST['generatePdfFilter']) {  
        case 'Concluidos':
            $filter = ' WHERE assistencia.dataFim IS NOT NULL';
            $Allassistances = $assistance->getAllWithFilter($filter);
            break;
        
        case 'Front Office':
            $filter = ' WHERE assistencia.frontOffice = "Sim";';
            $Allassistances = $assistance->getAllWithFilter($filter);
            break;

        case 'Em aberto':
            $filter = ' WHERE assistencia.dataFim IS NULL;';
            $Allassistances = $assistance->getAllWithFilter($filter);
            break;
        default:
            $Allassistances = $assistance->getAll();
            break;
    }
    
    $data = '';

    foreach($Allassistances as $assistanceData) {
        $data .= generateHeader('Assistência', $assistanceData->getId());
        $data .= '<p><strong>Data inicial: </strong>' . $assistanceData->getInitialDate() . '</p>';
        $data .= '<p><strong>Data final: </strong>' . $assistanceData->getFinalDate() . '</p>';
        $data .= '<p><strong>Duração: </strong>' . $assistanceData->getDuration() . 'h </p>';
        $data .= '<p><strong>Descrição: </strong>' . $assistanceData->getDescription() . '</p>';
        $data .= '<p><strong>Objetivos: </strong>' . $assistanceData->getGoals() . '</p>';
        $data .= '<p><strong>Front-Office: </strong>' . $assistanceData->getFrontOffice() . '</p>';
        $data .= '<p><strong>Tecnico responsável: </strong>' . $assistanceData->getTechnicalName() . '</p>';  
        $data .= '<p><strong>Tipo: </strong>' . $assistanceData->getTypeName() . '</p>';
        $data .= '<p><strong>Código interno do equipamento: </strong>' . $assistanceData->getEquipmentName() . '</p>';
        $data .= '<br>';
    }
    return $data;
}

function generateEquipment($pdo) {
    $equipment = new equipmentsDAOMS($pdo);
    $state = new statesDAOMS($pdo);

    switch ($_POST['generatePdfFilter']) {  
        case 'Ativos':
            $activeStateId = $state->getActiveStateId();

            if(!$activeStateId) {
                $activeStateId = $state->createActiveState();
            }

            $filter = ' WHERE equipamentos.estados_idestados = ' . $activeStateId . ';';
            $allEquipments = $equipment->getAllWithFilter($filter);
            break;

        case 'Emprestados':
            $filter = ' INNER JOIN emprestimos ON equipamentos.idEquipamentos = emprestimos.equipamentos_idEquipamentos;';
            $allEquipments = $equipment->getAllWithFilter($filter);
            break; 

            
        case 'Avariado':
            $filter = ' WHERE equipamentos.avarias_idavarias IS NOT NULL;';
            $allEquipments = $equipment->getAllWithFilter($filter);
            break; 
        default:
        $allEquipments = $equipment->getAll();
            break;
    }

    $data = '';

    foreach($allEquipments as $equipmentData) {
        $historicData = $equipment->getHistoric($equipmentData->getId());

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

        $data .= generateSubheader('Historico');
        foreach($historicData as $historic) {
            $data .= '<p><strong>Utilizador: </strong>' . $historic->getUser() . '</p>';
            $data .= '<p><strong>Data inicio: </strong>' . $historic->getInitialDate() . '</p>';
            $data .= '<p><strong>Data fim: </strong>' . $historic->getFinalDate() . '</p>';
            $data .= '<hr>';
        }
    }
    return $data;
}

function generateMalfuncion($pdo) {
    $malfunctions = new malfunctionsDAOMS($pdo);
    switch ($_POST['generatePdfFilter']) {  
        case 'Com assistências':
            $filter = ' WHERE avarias.assistencia_idAssistencia IS NOT NULL;';
            $allMalfunctions = $malfunctions->getAllWithFilter($filter);
            break;
        default:
            $allMalfunctions = $malfunctions->getAll();
            break;
    }

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

    switch ($_POST['generatePdfFilter']) {  
        case 'Caducados':
            $filter = ' WHERE DATE(softwares.dataFinal) <= DATE(NOW());';
            $allSoftwares = $softwares->getAllSoftwaresWithFilter($filter);
            break;
        default:
            $allSoftwares = $softwares->getAllSoftwares();
            break;
    }
    
    $data = '';

    foreach($allSoftwares as $softwareData) {
        $data .= generateHeader('Software', $softwareData->getId());
        $data .= '<p><strong>Tipo: </strong>' . $softwareData->getTypeName() . '</p>';
        $data .= '<p><strong>Versão: </strong>' . $softwareData->getVersion() . '</p>';
        $data .= '<p><strong>Chave: </strong>' . $softwareData->getKey() . '</p>';
        $data .= '<p><strong>Data inicial de contrato: </strong>' . $softwareData->getInitialDate() . '</p>';
        $data .= '<p><strong>Data final de contrato: </strong>' . $softwareData->getFinalDate() . '</p>';
    }
    return $data;
}

function generateLent($pdo) {
    $lent = new lentDAOMS($pdo);

    switch ($_POST['generatePdfFilter']) {  
        case 'Devolvidos':
            $filter = ' WHERE emprestimos.dataFim IS NOT NULL;';
            $allLent = $lent->getAllWithFilter($filter);
            break;

        case 'Em aberto':
            $filter = ' WHERE emprestimos.dataFim IS NULL;';
            $allLent = $lent->getAllWithFilter($filter);
            break;
        default:
            $allLent = $lent->getAll();
            break;
    }
    
    $data = '';

    foreach($allLent as $lent) {
        $data .= generateHeader('Emprestimo', $lent->getId());
        $data .= '<p><strong>Data inicial: </strong>' . $lent->getInitialDate() . '</p>';
        $data .= '<p><strong>Data final: </strong>' . $lent->getFinalDate() . '</p>';
        $data .= '<p><strong>Responsável: </strong>' . $lent->getUser() . '</p>';
        $data .= '<p><strong>Contacto: </strong>' . $lent->getContact() . '</p>';
        $data .= '<p><strong>Observações: </strong>' . $lent->getObs() . '</p>';
        $data .= '<p><strong>Equipamento emprestado: </strong>' . $lent->getEquipmentInternalCode() . '</p>';
    }
    return $data;
}

switch ($_POST['category']) {
    case 'Assistências':
        $pdfData = generateAssistance($pdo);
        break;

    case 'Equipamentos':
        $pdfData = generateEquipment($pdo);
        break;

    case 'Avarias':
        $pdfData = generateMalfuncion($pdo);
        break; 

    case 'Softwares':
        $pdfData = generateSoftware($pdo);
        break; 

    case 'Emprestimos':
        $pdfData = generateLent($pdo);
        break; 
    default:
        $_SESSION['generatePDFError'] = "Aparentemente ocorreu um problema ao determinar que tipo de PDF você quer gerar. Tente novamemente";
        
        break;
}

$date = date("Y-m-d");
$pdfData .= '<p> Gerado na data: ' . $date . '</p>';  

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($pdfData);
$mpdf->Output(); 


