<?php 
require_once '../config.php';
require_once '../dao/categorysDaoMS.php';
require_once '../dao/brandsDaoMS.php';
require_once '../dao/statesDaoMS.php';
require_once '../dao/providersDaoMS.php';
require_once '../dao/softwaresDaoMS.php';
require_once '../dao/equipmentsDaoMS.php';
session_start();

function getUrl($adress)
{
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?
        "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . '/geev2';

    echo $url . $adress;
}

if(!isset($_GET['id']) && !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['updateEquipmentError'] = 'Ocorreu um problema a encontrar o equipamento para o atualizar, tente novamente.';
    
    header('Location: ./home.php');
    die();
}

$id = $_GET['id'];

$equipments = new equipmentsDAOMS($pdo);
$categorys = new categorysDAOMS($pdo);
$brands = new brandsDAOMS($pdo);
$states = new statesDAOMS($pdo);
$providers = new providersDAOMS($pdo);
$softwares = new softwaresDAOMS($pdo);
$equipments = new equipmentsDAOMS($pdo);

$allSoftwaresType = $softwares->getAllSoftwareTypes();
$allSoftwares = $softwares->getAllSoftwares();
$allCategorys = $categorys->getAll();
$allBrands = $brands->getAll();
$allStates = $states->getAll();
$allProviders = $providers->getAll();
$equipmentData = $equipments->getSpecificById($id);
$allEquipments = $equipments->getAll();

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
                <form id="form" data-cookieName="__geeupdateequipment" action="<?php getUrl('/actions/updateEquipment.php?id=' . $id); ?>" method="post">
                    <input type="hidden" value="<?php echo $_GET['id'] ?>"name="id">
                    <div class="description dataContainer">
                        <h3>Descrição</h3>
                        <input class="input" required maxlength="20" value="<?php echo($equipmentData->getInternalCode());?>" placeholder="Código interno" type="text" name="internalCode" id="internalCode">

                        <div class="filter">
                            <select class="select required" id="type" name="type">
                                <option value="" selected disabled hidden>Selecione um tipo..</option>
                                <?php foreach ($allCategorys as $category) {
                                    echo ' <option> ' . $category->getCategoryName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="type" placeholder="Pesquisar por tipos..." type="text" name="filter">
                        </div>

                        <div class="filter">
                            <select class="select required" id="brand" name="brand">
                                <option value="" selected disabled hidden>Selecione uma marca..</option>
                                <?php foreach ($allBrands as $brand) {
                                    echo ' <option> ' . $brand->getBrandName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="brand" placeholder="Pesquisar por tipos..." type="text" name="filter">
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

                        <input class="input" maxlength="100" value="<?php echo($equipmentData->getModel());?>" placeholder="Modelo" type="text" name="model" id="model">
                        <input class="input" maxlength="32" required value="<?php echo($equipmentData->getSerieNumber());?>" placeholder="Código de série" type="text" name="serieNumber" id="serieNumber"/>

                        <div class="buttonsContainer">
                        <button class="btn equipmentsActionButton" data-modalId="createCategory">Criar categoria</button>
                            <button class="btn equipmentsActionButton" data-modalId="createBrand">Criar marca</button>
                            <button class="btn equipmentsActionButton" data-modalId="createState">Criar estado</button>
                        </div>
                    </div>

                    <div class="characterization dataContainer">
                        <h3>Caracterização</h3>
                        <textarea class="textarea" placeholder="Características" id="features" name="features"></textarea>

                        <textarea class="textarea" placeholder="Observações" id="obs" name="obs"></textarea>

                        <input class="input" value="<?php echo($equipmentData->getAcquisitionDate());?>" type="date" name="acquisitionDate">

                        <input maxlength="45" class="input" value="<?php echo($equipmentData->getPatrimonialCode());?>" placeholder="Código patrimonial" type="text" name="patrimonialCode" id="patrimonialCode">
                    </div>

                    <div class="location dataContainer">
                        <h3>Localização</h3>
                        <input maxlength="100" class="input" value="<?php echo($equipmentData->getUser());?>" placeholder="Utilizador" type="text" name="user" id="user">

                        <input maxlength="100" class="input" value="<?php echo($equipmentData->getLocation());?>" placeholder="Localização" type="text" name="location" id="location">

                        <input class="input" value="<?php echo($userDate);?>" type="date" name="userDate">
                    </div>

                    <div class="lanInformation dataContainer">
                        <h3>Informação de rede</h3>
                        <input class="input" maxlength="45" value="<?php echo($equipmentData->getLanPort());?>" placeholder="Porta de rede" type="text" name="lanPort" id="lanPort">
                        <input class="input" maxlength="100" value="<?php echo($equipmentData->getActiveEquipment());?>" placeholder="Equipamento Ativo" type="text" name="activeEquipment" id="activeEquipment">
                        <input class="input" maxlength="15" value="<?php echo($equipmentData->getIpAdress());?>" placeholder="Endereço IP" type="text" name="ipAdress" id="ipAdress">
                    </div>

                    <div class="providers dataContainer">
                        <h3>Fornecedor</h3>
                        <div class="filter">
                            <select class="select required" name="provider">
                                <option value="" selected disabled hidden>Selecione um fornecedor..</option>
                                <?php foreach ($allProviders as $provider) {
                                    echo ' <option> ' . $provider->getName() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" placeholder="Pesquisar por fornecedor.." type="text" name="filter">
                        </div>
                    </div>

                    <div class="softwares dataContainer">
                        <h3>Softwares</h3>
                        <div class="filter">
                            <select class="select" id="softwares" name="softwares">
                            <option value="" selected disabled hidden>Selecione um software..</option>
                                <?php foreach ($allSoftwares as $s) {
                                    echo ' <option> ' . $s->getTypeName() . ' - ' . $s->getVersion() . '</option> ';
                                } ?>
                            </select>

                            <input class="input" autocomplete="off" data-filterName="softwares" placeholder="Pesquisar por softwares.." type="text" name="filter">
                        </div>
                    </div>
                </form>
                <div class="submitContainer">
                    <button type="submit" form="form" class="btn">Atualizar equipamento</button>
                </div>
            </div>
        </div>

        <div class="modalFilter" id="modalFilter">
            <!--MODALS TO SIDEBAR -->
            <div id="equipmentModal" class="equipmentModal modalContent updateEquipment">
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
                <button id="actionModal" class="btn">Atualizar</button>
            </div>

            <div id="retireEquipment" class="equipmentModal modalContent retireEquipment">
                <h3>Olá, qual equipamento você quer abater?</h3>

                <form>
                    <select class="select" id="retireEquipmentsSelect" name="equipments">
                        <option value="" selected disabled hidden>Selecione um equipamento..</option>
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
                        <option value="" selected disabled hidden hidden>Selecione um software..</option>
                        <?php foreach ($allSoftwares as $software) {
                                    echo ' <option data-id="' . $software->getId() . '"> ' . $software->getTypeName() . ' - ' . $software->getVersion() . '</option> ';
                        } ?>
                    </select>

                    <input class="input" autocomplete="off" data-filtername="updateSoftwareSelect" placeholder="Pesquisar por softwares..." type="text" name="filter">
                </form>
                <button id="updateSoftwareBtnAction" class="btn">Atualizar</button>
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