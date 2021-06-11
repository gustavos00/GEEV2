<?php 
require_once '../config.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/equipmentsDaoMS.php';

function getUrl($adress)
{
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/geev2';

    echo $url . $adress;
}

$softwares = new softwaresDaoMS($pdo);
$providers = new providersDaoMS($pdo);
$equipments = new equipmentsDaoMS($pdo);

$AllProviders = $providers->getAll();
$allEquipments = $equipments->getAll();
$allSoftwares = $softwares->getAllSoftwares();
$allSoftwaresType = $softwares->getAllSoftwareTypes();
$allSoftwaresType = $softwares->getAllSoftwareTypes();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Web application to manage electronic equipment of Praia da Vitoria City Hall">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/3adda180c0.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../assets/img/server.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/styles/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/styles/global.css">
    <link rel="stylesheet" href="../assets/styles/sidebar.css">
    <link rel="stylesheet" href="../assets/styles/malfunction.css">

    <title>Atualizar avaria - GEE</title>
</head>
    <body>
        <!-- NAV BAR -->
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
                        <a href="createEquipment.php">• Criar equipamento</a>
                        <a data-doWhat="updateEquipment" class="openModalAction">• Atualizar equipamento</a>
                        <a href="home.php">• Visualizar equipamentos</a>
                        <a data-doWhat="retireEquipment" class="openModalAction">• Abater equipamento</a>
                        <a data-doWhat="deleteEquipment" class="openModalAction">• Apagar equipamento</a>
                        <a data-doWhat="lendEquipmentModal" class="openModalAction">• Emprestar equipamento</a>
                        <a data-doWhat="returnEquipmentModal" class="openModalAction">• Retornar equipamento de emprestimo</a>
                    </div>
                </div>

                <div class="dropdownContainer">
                    <div class="actionButton softwares">
                        <i class="far fa-plus-square"></i>
                        Softwares
                        <i id="arrow" class="arrow fas fa-arrow-right"></i>
                    </div>
                    <div data-dropdown="softwares" class="dropdownContent">
                        <a href="createSoftware.php">• Criar softwares</a>
                        <a data-doWhat="updateSoftware" class="openModalAction">• Atualizar softwares</a>
                        <a href="home.php#softwaresContainer">• Visualizar softwares</a>
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
                        <a href="createProvider.php">• Criar fornecedores</a>
                        <a data-doWhat="updateProvider" class="openModalAction">• Atualizar fornecedores</a>
                        <a href="home.php">• Visualizar fornecedores</a>
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
                        <a href="createMalfunction.php">• Criar avaria</a>
                        <a data-doWhat="updateMalfunction" class="openModalAction">• Atualizar avaria</a>
                        <a href="home.php#malfunctionsContainer">• Visualizar avarias</a>
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
                        <a href="createAssistance.php">• Criar assistência</a>
                        <a data-doWhat="updateAssistance" class="openModalAction">• Atualizar assistência</a>
                        <a href="home.php#malfunctionsContainer">• Visualizar assistência</a>
                        <a data-doWhat="deleteAssistance" class="openModalAction">• Apagar assistência</a>
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

        <!-- PAGE BODY -->
        <div class="contentWrap">
            <div class="container">
                <h1>Atualizar avaria</h1>
                    <?php
                    if (isset($_SESSION['createMalfunctionError'])) {
                        echo '
                        <div class="alert">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ' . $_SESSION['createMalfunctionError'] . '
                                <button type="button" class="btn-close unsetSessionVariable" data-session-name="createMalfunctionError" data-bs-dismiss="alert"></button>
                            </div>
                        </div>
                        ';
                    }
                    ?>

                <form id="form" data-cookieName="__geeupdatemalfunction" action="<?php getUrl('/actions/updateMalfunction.php'); ?>" method="post">
                    <div class="dataContainer">
                        <input class="input" type="date" name="dateMalfunction" id="dateMalfunction">

                        <textarea class="textarea" placeholder="Insira uma descrição..." name="description" id="description" cols="30" rows="10"></textarea>

                        <div class="filter">
                            <select class="select" id="provider" name="provider">
                                <option value="" selected disabled hidden>Selecione um fornecedor..</option>
                                <?php foreach ($allProviders as $provider) {
                                    echo ' <option>' . $provider->getName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="provider" placeholder="Pesquisar por tipos..." type="text" name="filter">
                        </div>


                        <div class="filterContainer">
                            <p>Caso já esteja uma assistência criada para esta avaria, escolha-a abaixo.</p>
                            <div class="filter">

                            <select class="select" id="assistance" name="assistance">
                                <option value="" selected disabled hidden>Selecione uma assistência..</option>
                                <?php foreach($allAssistances as $assistance) {  
                                    echo ' <option>' . $assistance->getTypeName() . '(' . $assistance->getInitialDate() . ' / ' . $assistance->getFinalDate() . ') </option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="assistance" placeholder="Pesquisar por tipos..." type="text" name="filter">
                            </div>
                        </div>
                        
                        <input class="btn" type="submit" value="Atualizar avaria">
                    </div>
                </form>
            </div>
        </div>
        
        <div class="modalFilter" id="modalFilter">
            <div id="equipmentModal" class="equipmentModal modalContent updateEquipment">
                <h3>Olá, qual equipamento você quer atualizar?</h3>

                <form>
                    <select class="select" id="updateEquipmentsSelect" name="equipments">
                        <option value="" selected disabled>Selecione um equipamento..</option>
                        <?php foreach ($allEquipments as $equipment) {
                            echo ' <option data-id="' . $equipment->getId() . '"> ' . $equipment->getInternalCode() . ' - ' . $equipment->getCategoryName() . ' (' . $equipment->getIpAdress() . ')' . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateEquipmentsSelect" placeholder="Pesquisar por equipamentos..." type="text" name="filter">
                </form>
                <button id="actionModal" class="btn">Atualizar</button>
            </div>

            <div id="retireEquipment" class="equipmentModal modalContent retireEquipment">
                <h3>Olá, qual equipamento você quer abater?</h3>

                <form>
                    <select class="select" id="retireEquipmentsSelect" name="equipments">
                        <option value="" selected disabled>Selecione um equipamento..</option>
                        <?php foreach ($allEquipments as $equipment) {
                            echo ' <option data-id="' . $equipment->getId() . '"> ' . $equipment->getInternalCode() . ' - ' . $equipment->getCategoryName() . ' (' . $equipment->getIpAdress() . ')' . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="retireEquipmentsSelect" placeholder="Pesquisar por equipamentos..." type="text" name="filter">
                </form>
                <button id="retireEquipmentBtAction" class="btn">Abater</button>
            </div>

            <div class="softwareModal updateSoftware modalContent" id="updateSoftware">
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
                <button id="updateSoftwareBtnAction" class="btn">Atualizar</button>
            </div>
        </div>

        <script src="../scripts/filterSystem.js"></script>
        <script src="../scripts/storeFormData.js"></script>
        <script src="../scripts/sidebarSystem.js"></script>
        <script src="../scripts/unsetSessionVariable.js"></script>
        <script src="../scripts/malfunctionSystem.js"></script>
    </body>
</html>