<?php
require_once '../config.php';
require_once '../dao/equipmentsDaoMS.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/malfunctionsDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/assistanceDaoMS.php';
require_once '../dao/lentDaoMS.php';
session_start();

function getUrl($adress)
{
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/geev2';

    echo $url . $adress;
}

$equipments = new equipmentsDAOMS($pdo);
$softwares = new softwaresDAOMS($pdo);
$malfunctions = new malfunctionsDAOMS($pdo);
$providers = new providersDAOMS($pdo);
$assistance = new assistanceDAOMS($pdo);
$lent = new lentDAOMS($pdo);

$AllMalfunctions = $malfunctions->getAll();
$allSoftwares = $softwares->getAllSoftwares();
$allEquipments = $equipments->getAll();
$allProviders = $providers->getAll();
$allAssistances = $assistance->getAll();
$allNotRetiredEquipments = $equipments->getAllNotRetiredEquipaments();
$AllNotLentEquipments = $equipments->getAllNotLentEquipments();
$allOpenLentProcess = $lent->getAllOpenLentProcess();
$allLentProcess = $lent->getAll();

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
    <link rel="stylesheet" href="../assets/styles/home.css">

    <title>Página inicial - GEE</title>
</head>
    <body>

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
                        <a target="_blank" href="../actions/generatePDF.php?who=equipments">• Gerar PDF com todos os equipamentos</a>
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
                        <a target="_blank" href="../actions/generatePDF.php?who=softwares">• Gerar PDF com todos os softwares</a>
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
                        <a target="_blank" href="../actions/generatePDF.php?who=malfunction">• Gerar PDF com todas as avarias</a>
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
                        <a target="_blank" href="../actions/generatePDF.php?who=assistance">• Gerar PDF com todas as assistências</a>
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
        <div class="container">

            <?php
                if (isset($_SESSION['successMessage'])) {
                    echo '
                    <div class="alert">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ' . $_SESSION['successMessage'] . '
                            <button type="button" class="btn-close unsetSessionVariable" data-session-name="successMessage" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    ';
                }
            ?>

            <div class="dataContainer equipments">
                <h3>Equipamentos</h3>

                <div class="dataContent">
                    <div class="filter">
                        <form method="POST">
                            <a href="#" class="searchBtn">
                                <i class="fas fa-search"></i>
                            </a>    
                            <input class="search-input" data-filterName="equipments" type="text" name="filter" placeholder="Pesquise por código interno, endereço IP...">
                        </form>
                    </div>

                    <div class="tableContainer">
                        <table id="equipments" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Código Interno</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Marca/Modelo</th>
                                    <th scope="col">Número de Série</th>
                                    <th scope="col">Endereço IP</th>
                                    <th scope="col">Tomada de Rede</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($allEquipments as $specificEquipment) : ?>
                                    <tr>
                                        <td><?= $specificEquipment->getInternalCode(); ?></td>
                                        <td><?= $specificEquipment->getCategoryName(); ?></td>
                                        <td><?php echo ($specificEquipment->getBrandName() . ' - ' . $specificEquipment->getModel()); ?></td>
                                        <td><?= $specificEquipment->getSerieNumber(); ?></td>
                                        <td><?= $specificEquipment->getIpAdress(); ?></td>
                                        <td><?= $specificEquipment->getLanPort(); ?></td>
                                        <td><?= $specificEquipment->getStateName(); ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="softwaresContainer" class="dataContainer softwares">
                <h3>Softwares</h3>

                <div class="dataContent">
                    <div class="filter">
                        <form method="POST">
                            <a href="#" class="searchBtn">
                                <i class="fas fa-search"></i>
                            </a>    
                            <input class="search-input" data-filterName="softwares" type="text" name="filter" placeholder="Pesquise por data inicial, data final, versão, tipo...">
                        </form>
                    </div>

                    <div class="tableContainer">
                        <table id="softwares" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Chave</th>
                                    <th scope="col">Versão</th>
                                    <th scope="col">Data inicial</th>
                                    <th scope="col">Data Final</th>
                                    <th scope="col">Fornecedor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($allSoftwares as $specificSoftware) :  ?>
                                    <tr>
                                        <td><?= $specificSoftware->getTypeName(); ?></td>
                                        <td><?= $specificSoftware->getKey(); ?></td>
                                        <td><?= $specificSoftware->getVersion(); ?></td>
                                        <td><?= $specificSoftware->getInitialDate(); ?></td>
                                        <td><?= $specificSoftware->getFinalDate(); ?></td>
                                        <td><?= $specificSoftware->getProviderName(); ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="malfunctionsContainer" class="dataContainer malfunctions">
                <h3>Avarias</h3>

                <div class="dataContent">
                    <div class="filter">
                        <form method="POST">
                            <a href="#" class="searchBtn">
                                <i class="fas fa-search"></i>
                            </a>    
                            <input class="search-input" data-filterName="malfunctions" type="text" name="filter" placeholder="Pesquise por data inicial, data final, versão, tipo...">
                        </form>
                    </div>

                    <div class="tableContainer">
                        <table id="malfunctions" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Data</th>
                                    <th scope="col">Descrição</th>
                                    <th scope="col">Fornecedor</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($AllMalfunctions as $malfunctions) : ?>
                                    <tr>
                                        <td><?= $malfunctions->getDate(); ?></td>
                                        <td><?= $malfunctions->getDescription(); ?></td>
                                        <td><?= $malfunctions->getProviderName(); ?></td>
                                        <td><?= $malfunctions->getStatus() ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="assistancesContainer" class="dataContainer assistances">
                <h3>Assistências</h3>

                <div class="dataContent">
                    <div class="filter">
                        <form method="POST">
                            <a href="#" class="searchBtn">
                                <i class="fas fa-search"></i>
                            </a>    
                            <input class="search-input" data-filterName="softwares" type="text" name="filter" placeholder="Pesquise por data inicial, data final, versão, tipo...">
                        </form>
                    </div>

                    <div class="tableContainer">
                        <table id="softwares" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Data início</th>
                                    <th scope="col">Data final</th>
                                    <th scope="col">Técnico</th>
                                    <th scope="col">Front Office</th>
                                    <th scope="col">Objetivos</th>
                                    <th scope="col">Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($allAssistances as $assistance) :  ?>
                                    <tr>
                                        <td><?= $assistance->getInitialDate(); ?></td>
                                        <td><?= $assistance->getFinalDate(); ?></td>
                                        <td><?= $assistance->getTechnical(); ?></td>
                                        <td><?= $assistance->getFrontOffice(); ?></td>
                                        <td><?= $assistance->getGoals(); ?></td>
                                        <td><?= $assistance->getTypeName(); ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="assistancesContainer" class="dataContainer assistances">
                <h3>Emprestimos</h3>

                <div class="dataContent">
                    <div class="filter">
                        <form method="POST">
                            <a href="#" class="searchBtn">
                                <i class="fas fa-search"></i>
                            </a>    
                            <input class="search-input" data-filterName="softwares" type="text" name="filter" placeholder="Pesquise por data inicial, data final, versão, tipo...">
                        </form>
                    </div>

                    <div class="tableContainer">
                        <table id="softwares" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Responsável</th>
                                    <th scope="col">Data inicio</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Equipamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($allEquipmentsLent as $equipmentLent) :  ?>
                                    <tr>
                                        <td><?=$equipmentLent->getUser();?></td>
                                        <td><?=$equipmentLent->getInitialDate();?></td>
                                        <td><?=$equipmentLent->getContact();?></td>
                                        <td><?=$equipmentLent->getEquipmentInternalCode();?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <input class="input" type="date" name="initialDate" id="initialDate">
                    <input class="input" type="date" name="finalDate" id="finalDate">
                    
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
                    <input class="input" type="date" name="finalDate" id="finalDate">
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

            <div data-actionBtn="updateProviderBtnAction" class="ProviderModal updateProvider modalContent" id="updateProvider">
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

            <div data-actionBtn="updateMalfunctionBtnAction" id="updateMalfunction" class="equipmentModal modalContent updateMalfunction">
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

            <div data-actionBtn="deleteMalfunctionBtnAction" id="deleteMalfunction" class="equipmentModal modalContent deleteMalfunction">
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

            <div data-actionBtn="updateAssistanceBtnAction" id="updateAssistance" class="equipmentModal modalContent updateAssistance">
                <h3>Olá, qual avaria você quer atualizar?</h3>

                <form>
                    <select class="select" id="updateAssistanceSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione uma assistência..</option>
                        <?php foreach($allAssistances as $assistances) {
                            echo '<option data-id=' . $assistances->getId() . '> ' . $assistances->getInitialDate() . ' - ' . $assistances->getTechnical() . ' (' . $assistances->getTypeName() . ') </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateAssistanceSelect" placeholder="Pesquisar por assistências..." type="text" name="filter">
                </form>
                <button data-who="updateAssistance" data-select="updateAssistanceSelect" id="updateAssistanceBtnAction" class="btn">Atualizar</button>
            </div>

            <div data-actionBtn="deleteAssistanceBtnAction" id="deleteAssistance" class="equipmentModal modalContent deleteAssistance">
                <h3>Olá, qual assistência você quer apagar?</h3>

                <form>
                    <select class="select" id="deleteAssistanceSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione uma assistência..</option>
                        <?php foreach($allAssistances as $assistances) {
                            echo '<option data-id=' . $assistances->getId() . '> ' . $assistances->getInitialDate() . ' - ' . $assistances->getTechnical() . ' (' . $assistances->getTypeName() . ') </option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="deleteAssistanceSelect" placeholder="Pesquisar por assistências..." type="text" name="filter">
                </form>
                < <button data-who="deleteAssistance" data-select="deleteAssistanceSelect" id="deleteAssistanceBtnAction" class="btn">Apagar</button>
            </div>
        </div>

        <script src="../scripts/filterSystem.js"></script>
        <script src="../scripts/sidebarSystem.js"></script>
        <script src="../scripts/unsetSessionVariable.js"></script>
    </body>
</html>