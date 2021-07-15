<?php
require_once '../config.php';
require_once '../dao/assistanceDaoMS.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/malfunctionsDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/lentDaoMS.php';
require_once '../dao/categorysDaoMS.php';
require_once '../dao/brandsDaoMS.php';
require_once '../dao/statesDaoMS.php';
session_start();

$equipments = new equipmentsDAOMS($pdo);
$malfunctions = new malfunctionsDAOMS($pdo);
$softwares = new softwaresDAOMS($pdo);
$providers = new providersDAOMS($pdo);
$categorys = new categorysDAOMS($pdo);
$brands = new brandsDAOMS($pdo);
$states = new statesDAOMS($pdo);
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
$equipmentData = $equipments->getSpecificById($_GET['id']);
$allCategorys = $categorys->getAll();
$allBrands = $brands->getAll();
$allStates = $states->getAll();


function getUrl($adress)
{
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/GEEV2';

    echo $url . $adress;
}

if(!isset($_GET['id']) && !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['indexErrorMessage'] = 'Ocorreu um problema a encontrar o equipamento para o atualizar, tente novamente.';
    
    header('Location: ./home.php');
    die();
}

$id = $_GET['id'];
$userDate = str_replace('-', '/', $equipmentData->getUserDate());

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
    <link rel="stylesheet" href="../assets/styles/equipments.css">

    <title>Atualizar equipamento - GEE</title>
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
            <h1>Atualizar equipamento</h1>

                <?php
                if (isset($_SESSION['updateEquipmentError'])) {
                    echo '
                    <div class="alert">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ' . $_SESSION['updateEquipmentError'] . '
                            <button type="button" class="btn-close unsetSessionVariable" data-session-name="updateEquipmentError" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    ';
                }
                ?>

                <form id="form" data-cookieName="__geeupdateequipment" action="<?php getUrl('/actions/createEquipment.php'); ?>" method="post">
                    <div class="description dataContainer">
                        <h3>Descrição</h3>
                        <input type="hidden" value="<?= $equipmentData->getId() ?>" id="id">
                        <input class="input" value="<?= $equipmentData->getInternalCode() ?>" required maxlength="20" placeholder="Código interno*" type="text" name="internalCode" id="internalCode">

                        <div class="filter">
                            <select class="select required" id="category" name="category">
                                <option value="" selected disabled hidden>Selecione uma categoria..</option>
                                <?php foreach ($allCategorys as $category) {
                                    echo ' <option> ' . $category->getCategoryName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="category" placeholder="Pesquisar por categorias..." type="text" name="filter">
                        </div>

                        <div class="filter">
                            <select class="select required" id="brand" name="brand">
                                <option value="" selected disabled hidden>Selecione uma marca..</option>
                                <?php foreach ($allBrands as $brand) {
                                    echo ' <option> ' . $brand->getBrandName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="brand" placeholder="Pesquisar por marcas..." type="text" name="filter">
                        </div>
                                            
                        <div class="filter">
                            <select class="select required" id="state" name="state">
                                <option value="" selected disabled hidden>Selecione um estado..</option>
                                <?php foreach ($allStates as $state) {
                                    echo ' <option> ' . $state->getState() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="state" placeholder="Pesquisar por estados..." type="text" name="filter">
                        </div>

                        <input class="input" value="<?= $equipmentData->getModel() ?>" maxlength="100" placeholder="Modelo" type="text" name="model" id="model">
                        <input class="input" value="<?= $equipmentData->getSerieNumber() ?>" maxlength="32" placeholder="Código de série" type="text" name="serieNumber" id="serieNumber"/>
                        
                        <div class="buttonsContainer">
                            <button class="btn smallBtn equipmentsActionButton" data-modalId="createCategoryModal">Criar categoria</button>
                            <button class="btn smallBtn equipmentsActionButton" data-modalId="createBrandModal">Criar marca</button>
                            <button class="btn smallBtn equipmentsActionButton" data-modalId="createStateModal">Criar estado</button>
                        </div>
                    </div>

                    <div class="characterization dataContainer">
                        <h3>Caracterização</h3>
                        <textarea class="textarea" value="<?= $equipmentData->getFeatures() ?>"  placeholder="Características" id="features" name="features"></textarea>

                        <textarea class="textarea" value="<?= $equipmentData->getObs() ?>" placeholder="Observações" id="obs" name="obs"></textarea>

                        <input class="input" value="<?= $equipmentData->getAcquisitionDate() ?>" placeholder="Data de aquisição" onfocus="(this.type='date')" onblur="(this.type='text')" id="acquisitionDate" name="acquisitionDate">> 

                        <input maxlength="45" value="<?= $equipmentData->getPatrimonialCode() ?>" class="input" placeholder="Código patrimonial" type="text" name="patrimonialCode" id="patrimonialCode">
                    </div>

                    <div class="location dataContainer">
                        <h3>Localização</h3>
                        <input maxlength="100" class="input" value="<?= $equipmentData->getUser() ?>" placeholder="Utilizador" type="text" name="user" id="user">

                        <input maxlength="100" class="input" value="<?= $equipmentData->getLocation() ?>" placeholder="Localização" type="text" name="location" id="location">

                        <input id="userDate" class="input" value="<?= $equipmentData->getUserDate() ?>" placeholder="Data de atribuição ao utilizador" onfocus="(this.type='date')" onblur="(this.type='text')" name="userDate">
                    </div>

                    <div class="lanInformation dataContainer">
                        <h3>Informação de rede</h3>
                        <input class="input" maxlength="45" value="<?= $equipmentData->getLanPort() ?>" placeholder="Porta de rede" type="text" name="lanPort" id="lanPort">
                        <input class="input" maxlength="100" value="<?= $equipmentData->getActiveEquipment() ?>" placeholder="Equipamento Ativo" type="text" name="activeEquipment" id="activeEquipment">
                        <input class="input" maxlength="15" value="<?= $equipmentData->getIpAdress() ?>" placeholder="Endereço IP" type="text" name="ipAdress" id="ipAdress">
                    </div>

                    <div class="providers dataContainer">
                        <h3>Fornecedor</h3>
                        <div class="filter">
                            <select class="select required" id="provider" name="provider">
                                <option value="" selected disabled hidden>Selecione um fornecedor..</option>
                                <?php foreach ($allProviders as $provider) {
                                    echo ' <option> ' . $provider->getName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="provider" placeholder="Pesquisar por fornecedor.." type="text" name="filter">
                        </div>

                        <a href="createProvider.php" class="btn smallBtn" >Criar fornecedor</a>
                    </div>

                    <div class="softwares dataContainer">
                        <h3>Softwares</h3>
                        <div class="filter">
                            <select class="select" id="softwares" name="softwares">
                            <option value="" selected disabled hidden>Selecione um software..</option>
                                <?php foreach ($allSoftwares as $s) {
                                    echo ' <option data-id="' . $s->getId() . '"> ' . $s->getTypeName() . ' - ' . $s->getVersion() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="softwares" placeholder="Pesquisar por softwares.." type="text" name="filter">
                        </div>

                        
                        <div class="softwareBtnsContainer">
                            <a href="createSoftware.php" class="btn smallBtn" >Criar software</a>
                            <button class="btn smallBtn" id="addSoftwareBtn">Adicionar software</button>
                        </div>

                        <div class="tableContainer">
                            <table class="table table-hover table-striped" id="providerContacts">
                                <thead>
                                    <tr>
                                        <th scope="col">Software</th>
                                        <th scope="col">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="submitContainer">
                        <button type="submit" form="form" id="submitFormBtn" class="btn">Atualizar equipamento</button>
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

            <!--MODALS TO CURRRENT PAGE -->

            <div data-actionbuttonid="createBrandAction" data-who="brand" class="equipmentModal modalContent createBrand" id="createBrand">
                <h3>Criar marca</h3>

                <form>
                    <input class="input" type="text" placeholder="Nome da marca"/>

                    <button class="btn" id="createBrandAction" type="submit" >Criar marca</button>
                </form>
            </div>

            
            <div data-actionbuttonid="createCategoryAction" data-who="category" class="equipmentModal modalContent createCategory" id="createCategory">
                <h3>Criar categoria</h3>

                <form>
                        <input class="input" type="text" placeholder="Nome da categoria"/>
                        <input class="input" type="text" placeholder="Código da categoria"/>

                        <button class="btn" id="createCategoryAction" type="submit" >Criar categoria</button>
                </form>
            </div>

            
            <div data-actionbuttonid="createStateAction" data-who="state" class="equipmentModal modalContent createState" id="createState"> 
                <h3>Criar estado</h3>

                <form>
                    <input class="input" type="text" placeholder="Nome do estado"/>

                    <button class="btn" id="createStateAction" type="submit" >Criar estado</button>
                </form>
            </div>
        </div>
        
        <script src="../scripts/filterSystem.js"></script>
        <script src="../scripts/equipmentSystem.js"></script>
        <script src="../scripts/storeFormData.js"></script>
        <script src="../scripts/sidebarSystem.js"></script>
        <script src="../scripts/unsetSessionVariable.js"></script>
    </body>
</html>