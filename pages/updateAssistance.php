<?php 
require_once '../config.php';
require_once '../dao/assistanceDaoMS.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/malfunctionsDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/lentDaoMS.php';
session_start();

$equipments = new equipmentsDAOMS($pdo);
$malfunctions = new malfunctionsDAOMS($pdo);
$softwares = new softwaresDAOMS($pdo);
$providers = new providersDAOMS($pdo);
$assistance = new assistanceDAOMS($pdo);
$lent = new lentDAOMS($pdo);

$AllMalfunctions = $malfunctions->getAll();

$allSoftwares = $softwares->getAllSoftwares();

$allProviders = $providers->getAll();

$allAssistances = $assistance->getAll();

$allEquipments = $equipments->getAll();
$allNotRetiredEquipments = $equipments->getAllNotRetiredEquipaments();
$AllNotLentEquipments = $equipments->getAllNotLentEquipments();

$allLentProcess = $lent->getAll();

//For the page
$assistanceData = $assistance->getSpecific($_GET['id']);
$allAssistanceTypes = $assistance->getAllAssistanceTypes();


if(!isset($_GET['id']) && !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['indexErrorMessage'] = 'Ocorreu um problema a encontrar a assistência para o atualizar, tente novamente.';
    
    header('Location: ./home.php');
    die();
}


function getUrl($adress)
{
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/GEEV2';

    echo $url . $adress;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/3adda180c0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../assets/img/server.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/styles/global.css">
    <link rel="stylesheet" href="../assets/styles/sidebar.css">
    <link rel="stylesheet" href="../assets/styles/assistance.css">

    <title>Atualizar assistência - GEE</title>
</head>
    <body>
        <div class="sidebarWrapper">
            <nav class="sidebar">
                <div class="sidebarBtnContainer">
                    <div class="sidebarBtn"></div>
                </div>

                <div class="actionsButtonsContainer">
                    <div class="dropdownContainer">
                        <div class="actionButton equipment">
                            <i class="fas fa-desktop"></i>
                            Equipamento
                            <i id="arrow" class="arrow fas fa-arrow-right"></i>
                        </div>
                        <div data-dropdown="equipamento" class="dropdownContent">
                            <a href="home.php">• Visualizar equipamentos</a>
                            <a href="createEquipment.php">• Criar equipamento</a>
                            <a data-doWhat="updateEquipment" class="openModalAction">• Atualizar equipamento</a>
                            <a data-doWhat="retireEquipment" class="openModalAction">• Abater equipamento</a>
                            <a data-doWhat="deleteEquipment" class="openModalAction">• Apagar equipamento</a>
                            <a data-doWhat="lendEquipmentModal" class="openModalAction">• Emprestar equipamento</a>
                            <a data-doWhat="returnEquipmentModal" class="openModalAction">• Retornar equipamento de emprestimo</a>
                            <a data-doWhat="deleteLentProcess" class="openModalAction">• Apagar processo de emprestimo</a>
                        </div>
                    </div>

                    <div class="dropdownContainer">
                        <div class="actionButton softwares">
                            <i class="far fa-plus-square"></i>
                            Softwares
                            <i id="arrow" class="arrow fas fa-arrow-right"></i>
                        </div>
                        <div data-dropdown="softwares" class="dropdownContent">
                            <a href="home.php#softwaresContainer">• Visualizar softwares</a>
                            <a href="createSoftware.php">• Criar softwares</a>
                            <a data-doWhat="updateSoftware" class="openModalAction">• Atualizar softwares</a>
                            <a data-doWhat="deleteSoftware" class="openModalAction">• Apagar softwares</a>
                        </div>
                    </div>

                    <div class="dropdownContainer">
                        <div class="actionButton providers">
                        <i class="fas fa-globe-europe"></i>
                            Fornecedores
                            <i id="arrow" class="arrow fas fa-arrow-right"></i>
                        </div>
                        <div data-dropdown="fornecedores" class="dropdownContent">
                            <a href="home.php">• Visualizar fornecedores</a>
                            <a href="createProvider.php">• Criar fornecedores</a>
                            <a data-doWhat="updateProvider" class="openModalAction">• Atualizar fornecedores</a>
                            <a data-doWhat="deleteProvider" class="openModalAction">• Apagar fornecedores</a>
                        </div>
                    </div>

                    <div class="dropdownContainer">
                        <div class="actionButton malfunctions">
                            <i class="fas fa-times"></i>
                            Avarias
                            <i id="arrow" class="arrow fas fa-arrow-right"></i>
                        </div>
                        <div data-dropdown="avarias" class="dropdownContent">
                            <a href="home.php#malfunctionsContainer">• Visualizar avarias</a>
                            <a href="createMalfunction.php">• Criar avaria</a>
                            <a data-doWhat="updateMalfunction" class="openModalAction">• Atualizar avaria</a>
                            <a data-doWhat="deleteMalfunction" class="openModalAction">• Apagar avarias</a>
                        </div>
                    </div>

                    <div class="dropdownContainer">
                        <div class="actionButton malfunctions">
                            <i class="fas fa-life-ring"></i>
                            Assistências
                            <i id="arrow" class="arrow fas fa-arrow-right"></i>
                        </div>
                        <div data-dropdown="assistências" class="dropdownContent">
                            <a href="home.php#malfunctionsContainer">• Visualizar assistência</a>
                            <a href="createAssistance.php">• Criar assistência</a>
                            <a data-doWhat="updateAssistance" class="openModalAction">• Atualizar assistência</a>
                            <a data-doWhat="deleteAssistance" class="openModalAction">• Apagar assistência</a>
                        </div>
                    </div>

                    <div class="dropdownContainer">
                        <div id="openPdfsModal" class="T pdfs">
                            <i class="fas fa-life-ring"></i>
                            Gerar PDF
                            <i id="arrow" class="arrow fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
                <div class="darkmodeSwitchContainer">
                    <label class="darkmodeSwitchContent" for="checkbox">
                        <input type="checkbox" id="checkbox" />
                        <div class="slider round"></div>
                    </label>
                </div>
            </nav>
        </div>

        <div class="contentWrap">
            <div class="container">
                <h1>Criar assistência</h1>
                    <?php
                    if (isset($_SESSION['updateAssistanceError'])) {
                        echo '
                        <div class="alert">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ' . $_SESSION['updateAssistanceError'] . '
                                <button type="button" class="btn-close unsetSessionVariable" data-session-name="updateAssistanceError" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                        ';
                    }
                    ?>

                <form id="form" data-cookieName="__geeupdatemalfunction" action="<?php getUrl('/actions/updateAssistance.php'); ?>" method="post">
                    <div class="dataContainer">
                        <input class="input" value=<?php echo($assistanceData->getInitialDate()); ?>  placeholder="Data inicial" onfocus="(this.type='date')" onblur="(this.type='text')"  name="initialDateAssistance" id="initialDateAssistance">
                        <input class="input"  placeholder="Data final" onfocus="(this.type='date')" onblur="(this.type='text')"  name="finalDateAssistance" value=<?php echo($assistanceData->getFinalDate()); ?> id="finalDateAssistance">

                        <textarea class="textarea"  placeholder="Insira uma descrição..."  name="description" id="description" cols="30" rows="10"><?php echo($assistanceData->getDescription()); ?></textarea>
                        <textarea class="textarea"  placeholder="Insira um objetivo..." name="objective" id="objective" cols="30" rows="10"><?php echo($assistanceData->getGoals()); ?></textarea>

                        <div class="frontOfficeContainer">
                            <label for="frontOffice">FrontOffice</label>
                            <input type="checkbox" name="frontOffice" id="frontOffice">
                        </div>

                        <div class="filter">
                            <select class="select" id="technical" name="technical">
                                <option value="" selected disabled hidden>Selecione um tecnico..</option>
                                <?php foreach ($allProviders as $provider) {
                                    echo ' <option>' . $provider->getName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="technical" placeholder="Pesquisar por tecnicos..." type="text" name="filter">
                        </div>

                        <div class="filter">
                            <select class="select" id="assistanceType" name="assistanceType">
                                <option value="" selected disabled hidden>Selecione um tipo de ocorrencia..</option>
                                <?php foreach ($allAssistanceTypes as $type) {
                                    echo ' <option>' . $type->getTypeName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="assistanceType" placeholder="Pesquisar por tipos..." type="text" name="filter">
                        </div>

                        <div class="filter">
                            <select class="select" id="equipments" name="equipments">
                                <option value="" selected disabled hidden>Selecione um equipamento..</option>
                                <?php foreach ($allEquipments as $equipment) {
                                    echo ' <option>' . $equipment->getInternalCode() . ' (' . $equipment->getIpAdress() . ') </option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="equipments" placeholder="Pesquisar por equipamentos..." type="text" name="filter">
                        </div>
                        
                        <input id="actionButton" data-who="createMalfunction" class="btn" type="submit" value="Atualizar avaria">
                    </div>
                </form>
            </div>
        </div>


        <div class="modalFilter" id="modalFilter">
            <!--MODALS TO SIDEBAR -->
            <div data-actionBtn="updateEquipmentBtnAction" id="updateEquipment" class="equipmentModal modalContent updateEquipment">
                <h3>Olá, qual equipamento você quer atualizar?</h3>

                <form>
                    <select class="select" id="updateEquipmentsSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione um equipamento..</option>
                        <?php foreach ($allEquipments as $equipment) {
                            echo ' <option data-id="' . $equipment->getId() . '"> ' . $equipment->getInternalCode() . ' - ' . $equipment->getCategoryName() . ' (' . $equipment->getIpAdress() . ')' . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateEquipmentsSelect" placeholder="Pesquisar por equipamentos..." type="text" name="filter">
                </form>
                <button data-who="updateEquipment" data-select="updateEquipmentsSelect" id="updateEquipmentBtnAction" class="btn">Atualizar</button>
            </div>

            <div data-actionBtn="retireEquipmentBtnAction" id="retireEquipment" class="equipmentModal modalContent retireEquipment">
                <h3>Olá, qual equipamento você quer abater?</h3>

                <form>
                    <select class="select" id="retireEquipmentsSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione um equipamento..</option>
                        <?php foreach ($allNotRetiredEquipments as $equipment) {
                            echo ' <option data-id="' . $equipment->getId() . '"> ' . $equipment->getInternalCode() . ' - ' . $equipment->getCategoryName() . ' (' . $equipment->getIpAdress() . ')' . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="retireEquipmentsSelect" placeholder="Pesquisar por equipamentos..." type="text" name="filter">
                </form>
                <button data-who="retireEquipment" data-select="retireEquipmentsSelect" id="retireEquipmentBtnAction" class="btn">Abater</button>
            </div>

            <div data-actionBtn="deleteEquipmentBtnAction" id="deleteEquipment" class="equipmentModal modalContent deleteEquipment">
                <h3>Olá, qual equipamento você quer apagar?</h3>

                <form>
                    <select class="select" id="deleteEquipmentSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione um equipamento..</option>
                        <?php foreach ($allEquipments as $equipment) {
                            echo ' <option data-id="' . $equipment->getId() . '"> ' . $equipment->getInternalCode() . ' - ' . $equipment->getCategoryName() . ' (' . $equipment->getIpAdress() . ')' . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="deleteEquipmentSelect" placeholder="Pesquisar por equipamentos..." type="text" name="filter">
                </form>
                <button data-who="deleteEquipment" data-select="deleteEquipmentSelect" id="deleteEquipmentBtnAction" class="btn">Apagar</button>
            </div>

            <div data-actionBtn="lendEquipmentBtnAction" id="lendEquipmentModal" class="equipmentModal modalContent lendEquipmentModal">
                <h3>Olá, qual equipamento você quer emprestar?</h3>

                <form id="lendEquipmentForm" action="<?php getUrl('/actions/lendEquipment.php'); ?>" method="post">
                    <input type="hidden" name="selectedEquipmentId" id="selectedEquipmentId">
                    <input class="input" placeholder="Data inicial" onfocus="(this.type='date')" onblur="(this.type='text')" name="initialDate" id="initialDate">
                    <input class="input" placeholder="Data final" onfocus="(this.type='date')" onblur="(this.type='text')" name="finalDate" id="finalDate">
                    
                    <input class="input" required maxlength="50" placeholder="Responsável pelo emprestimo..." type="text" name="responsibleUser" id="responsibleUser">
                    <input class="input" placeholder="Contacto...." type="text" name="contact" id="contact">

                    <textarea class="textarea" placeholder="Observações..." name="obs" id="obs" cols="30" rows="10"></textarea>

                    <div class="filter">
                        <select class="select" id="lendEquipmentSelect" name="equipments">
                            <option value="" selected disabled hidden>Selecione um equipamento..</option>
                            <?php foreach ($AllNotLentEquipments as $notLentEquipment) {
                                echo ' <option data-id="' . $notLentEquipment->getId() . '"> ' . $notLentEquipment->getInternalCode() . ' - ' . $notLentEquipment->getCategoryName() . ' (' . $notLentEquipment->getIpAdress() . ')' . '</option> ';
                            } ?>
                        </select>

                        <input class="input" autocomplete="off" data-filtername="lendEquipmentSelect" placeholder="Pesquisar por equipamentos..." type="text" name="filter">
                    </div>
                </form>
                <input type="submit" form="lendEquipmentForm" data-hiddenInput="selectedEquipmentId" data-who="lendEquipment" data-select="lendEquipmentSelect" id="lendEquipmentBtnAction" value="Emprestar" class="btn"/>
            </div>
            
            <div data-actionBtn="returnEquipmentBtnAction" id="returnEquipmentModal" class="equipmentModal modalContent returnEquipment">
                <h3>Olá, qual equipamento você quer retornar?</h3>

                <form id="returnEquipmentForm" action="<?php getUrl('/actions/returnEquipment.php'); ?>" method="post">
                    <input type="hidden" name="selectedEquipmentId" id="returnEquipmentId">
                    <input class="input"  placeholder="Data final" onfocus="(this.type='date')" onblur="(this.type='text')" name="finalDate" id="finalDate">
                    <select class="select" id="returnEquipmentSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione um equipamento..</option>
                        <?php foreach ($allNotRetiredEquipments as $lentEquipment) {
                            echo ' <option data-id="' . $lentEquipment->getId() . '"> ' . $lentEquipment->getInternalCode() . ' - ' . $lentEquipment->getCategoryName() . ' (' . $lentEquipment->getIpAdress() . ')' . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="returnEquipmentSelect" placeholder="Pesquisar por avarias..." type="text" name="filter">
                </form>
                <input type="submit" form="returnEquipmentForm" data-hiddenInput="returnEquipmentId" data-who="returnEquipment" data-select="returnEquipmentSelect" id="returnEquipmentBtnAction" value="Retornar" class="btn"/>
            </div>

            <div data-actionBtn="deleteLentProcessBtnAction" id="deleteLentProcess" class="equipmentModal modalContent deleteLentProcess">
                <h3>Olá, qual processo de emprestimo você quer apagar?</h3>

                <form>
                    <select class="select" id="deleteLentProcessSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione um processo de emprestimo..</option>
                        <?php foreach($allLentProcess as $lentProcess) {
                            echo ' <option data-id="' . $lentProcess->getId() . '"> ' . $lentProcess->getInitialDate() . ' - ' . $lentProcess->getFinalDate() . ' (' . $lentProcess->getEquipmentInternalCode() . ')' . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="deleteLentProcessSelect" placeholder="Pesquisar por assistências..." type="text" name="filter">
                </form>
                < <button data-who="deleteLentProcess" data-select="deleteLentProcessSelect" id="deleteLentProcessBtnAction" class="btn">Apagar</button>
            </div>

            <div data-actionBtn="updateSoftwareBtnAction" class="softwareModal updateSoftware modalContent" id="updateSoftware">
                <h3>Olá, qual software você quer atualizar?</h3>

                <form>
                    <select class="select" id="updateSoftwareSelect" name="softwares">
                        <option value="" selected disabled hidden>Selecione um software..</option>
                        <?php foreach ($allSoftwares as $software) {
                            echo ' <option data-id="' . $software->getId() . '"> ' . $software->getTypeName() . ' - ' . $software->getVersion() . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateSoftwareSelect" placeholder="Pesquisar por softwares..." type="text" name="filter">
                </form>
                <button data-who="updateSoftware" data-select="updateSoftwareSelect" id="updateSoftwareBtnAction" class="btn">Atualizar</button>
            </div>

            <div data-actionBtn="deleteSoftwareBtnAction" class="softwareModal deleteSoftware modalContent" id="deleteSoftware">
                <h3>Olá, qual software você quer apagar?</h3>

                <form>
                    <select class="select" id="deleteSoftwareSelect" name="softwares">
                        <option value="" selected disabled hidden>Selecione um software..</option>
                        <?php foreach ($allSoftwares as $software) {
                            echo ' <option data-id="' . $software->getId() . '"> ' . $software->getTypeName() . ' - ' . $software->getVersion() . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="deleteSoftwareSelect" placeholder="Pesquisar por softwares..." type="text" name="filter">
                </form>
                <button data-who="deleteSoftware" data-select="deleteSoftwareSelect" id="deleteSoftwareBtnAction" class="btn">Apgar</button>
            </div>

            <div data-actionBtn="updateProviderBtnAction" class="providerModal updateProvider modalContent" id="updateProvider">
                <h3>Olá, qual fornecedor você quer atualizar?</h3>

                <form>
                    <select class="select" id="updateProviderSelect" name="Providers">
                        <option value="" selected disabled hidden>Selecione um fornecedor..</option>
                        <?php foreach ($allProviders as $provider) {
                            echo ' <option data-id= ' . $provider->getId() . '>' . $provider->getName() . ' </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateProviderSelect" placeholder="Pesquisar por fornecedores..." type="text" name="filter">
                </form>
                <button data-who="updateProvider" data-select="updateProviderSelect" id="updateProviderBtnAction" class="btn">Atualizar</button>
            </div>

            <div data-actionBtn="deleteProviderBtnAction" class="ProviderModal deleteProvider modalContent" id="deleteProvider">
                <h3>Olá, qual fornecedor você quer apagar?</h3>

                <form>
                    <select class="select" id="deleteProviderSelect" name="Providers">
                        <option value="" selected disabled hidden>Selecione um fornecedor..</option>
                        <?php foreach ($allProviders as $provider) {
                            echo ' <option data-id= ' . $provider->getId() . '>' . $provider->getName() . ' </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="deleteProviderSelect" placeholder="Pesquisar por fornecedores..." type="text" name="filter">
                </form>
                <button data-who="deleteProvider" data-select="deleteProviderSelect" id="deleteProviderBtnAction" class="btn">Apagar</button>
            </div>

            <div data-actionBtn="updateMalfunctionBtnAction" id="updateMalfunction" class="malfunctionModal modalContent updateMalfunction">
                <h3>Olá, qual avaria você quer atualizar?</h3>

                <form>
                    <select class="select" id="updateMalfunctionSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione uma avaria..</option>
                        <?php foreach($AllMalfunctions as $malfunctions) {
                            echo '<option data-id=' . $malfunctions->getId() . '> ' . $malfunctions->getDate() . ' - ' . $malfunctions->getProviderName() . ' (' . $malfunctions->getStatus() . ') </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateMalfunctionSelect" placeholder="Pesquisar por avarias..." type="text" name="filter">
                </form>
                <button data-who="updateMalfunction" data-select="updateMalfunctionSelect" id="updateMalfunctionBtnAction" class="btn">Atualizar</button>
            </div>

            <div data-actionBtn="deleteMalfunctionBtnAction" id="deleteMalfunction" class="malfunctionModal modalContent deleteMalfunction">
                <h3>Olá, qual avaria você quer apagar?</h3>

                <form>
                    <select class="select" id="deleteMalfunctionSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione uma avaria..</option>
                        <?php foreach($AllMalfunctions as $malfunctions) {
                            echo '<option data-id=' . $malfunctions->getId() . '> ' . $malfunctions->getDate() . ' - ' . $malfunctions->getProviderName() . ' (' . $malfunctions->getStatus() . ') </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="deleteMalfunctionSelect" placeholder="Pesquisar por avarias..." type="text" name="filter">
                </form>
                < <button data-who="deleteMalfunction" data-select="deleteMalfunctionSelect" id="deleteMalfunctionBtnAction" class="btn">Apagar</button>
            </div>

            <div data-actionBtn="updateAssistanceBtnAction" id="updateAssistance" class="malfunctionModal modalContent updateAssistance">
                <h3>Olá, qual avaria você quer atualizar?</h3>

                <form>
                    <select class="select" id="updateAssistanceSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione uma assistência..</option>
                        <?php foreach($allAssistances as $assistances) {
                            echo '<option data-id=' . $assistances->getId() . '> ' . $assistances->getInitialDate() . ' - ' . $assistances->getTechnicalName() . ' (' . $assistances->getTypeName() . ') </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateAssistanceSelect" placeholder="Pesquisar por assistências..." type="text" name="filter">
                </form>
                <button data-who="updateAssistance" data-select="updateAssistanceSelect" id="updateAssistanceBtnAction" class="btn">Atualizar</button>
            </div>

            <div data-actionBtn="deleteAssistanceBtnAction" id="deleteAssistance" class="assistanceModal modalContent deleteAssistance">
                <h3>Olá, qual assistência você quer apagar?</h3>

                <form>
                    <select class="select" id="deleteAssistanceSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione uma assistência..</option>
                        <?php foreach($allAssistances as $assistances) {
                            echo '<option data-id=' . $assistances->getId() . '> ' . $assistances->getInitialDate() . ' - ' . $assistances->getTechnicalName() . ' (' . $assistances->getTypeName() . ') </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="deleteAssistanceSelect" placeholder="Pesquisar por assistências..." type="text" name="filter">
                </form>
                < <button data-who="deleteAssistance" data-select="deleteAssistanceSelect" id="deleteAssistanceBtnAction" class="btn">Apagar</button>
            </div>

            <div data-actionBtn="generatePdfActionBtn" id="generatePdf" class="equipmentModal modalContent generatePdf">
                <h3>Olá, qual é o PDF que deseja gerar?</h3>

                <form id="generatePdF" action="<?php getUrl('/actions/generatePDF.php'); ?>" method="post">
                    <select class="select" id="generatePdfSelect" name="category">
                        <option value="" selected disabled hidden>Selecione uma categoria..</option>
                        <option>Equipamentos</option>
                        <option>Softwares</option>
                        <option>Avarias</option>
                        <option>Assistências</option>
                        <option>Emprestimos</option>
                    </select>

                    <select class="select" id="generatePdfFilter" name="generatePdfFilter">
                        <option id="withoutFilterOption" value="" selected>Sem filtro</option>
                    </select>
                
                    <input type="submit" data-who="generatePdf" data-select="generatePdfSelect" id="generatePdfActionBtn" value="Gerar" class="btn">
                </form>
            </div>
        </div>

        <script src="../scripts/filterSystem.js"></script>
        <script src="../scripts/sidebarSystem.js"></script>
        <script src="../scripts/storeFormData.js"></script>
        <script src="../scripts/unsetSessionVariable.js"></script>
    </body>
</html>