<?php require_once '../models/equipments.php';

class equipmentsDAOMS implements equipmentsDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function getAll()
    {
        $equipmentData = [];

        $sql = $this->pdo->prepare(
            "SELECT equipamentos.*, prestadorservicos.nome, estados.estado, marca.nomeMarca, categoria.nomecategoria FROM equipamentos
            LEFT JOIN estados ON equipamentos.estados_idestados = estados.idestados
            LEFT JOIN marca ON equipamentos.marca_idmarca = marca.idmarca
            LEFT JOIN categoria ON equipamentos.categoria_idcategoria = categoria.idcategoria
            LEFT JOIN prestadorservicos ON equipamentos.prestadorservicos_idprestadorservico = prestadorservicos.idprestadorservico"
        );

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new equipments();

                $eq->setId($item['idEquipamentos']);
                $eq->setInternalCode($item['codInterno']);
                $eq->setSerieNumber($item['nSerie']);
                $eq->setFeatures(ucwords(strtolower($item['caracteristicas'])));
                $eq->setObs(ucwords(strtolower($item['observacoes'])));
                $eq->setPatrimonialCode($item['codPatrimonial']);
                $eq->setIpAdress($item['enderecoIp']);
                $eq->setAcquisitionDate($item['dataAdquisicao']);
                $eq->setLanPort($item['tomadaDeRede']);
                $eq->setUser($item['responsavel']);
                $eq->setUserDate($item['dataResponsavel']);
                $eq->setModel($item['modelo']);
                $eq->setLocation($item['localizacao']);
                $eq->setActiveEquipment($item['equipamentoAtivo']);

                $eq->setCategoryId($item['categoria_idCategoria']);
                $eq->setStateId($item['estados_idestados']);
                $eq->setBrandId($item['marca_idmarca']);
                $eq->setProviderId($item['prestadorServicos_idprestadorServico']);

                $eq->setCategoryName(ucwords(strtolower($item['nomecategoria'])));
                $eq->setStateName(ucwords(strtolower($item['estado'])));
                $eq->setBrandName($item['nomeMarca']);
                $eq->setProviderName($item['nome']);

                $equipmentData[] =  $eq;
            }
        }
        return $equipmentData;
    }

    public function createEquipment(equipments $e) {
        $sql = $this->pdo->prepare("
        INSERT INTO `equipamentos`(`codInterno`, `modelo`, `nSerie`, `caracteristicas`, `observacoes`, `dataadquisicao`, `codpatrimonial`, `enderecoip`, `tomadaderede`, `responsavel`, `dataresponsavel`, `equipamentoativo`, `localizacao`, `categoria_idCategoria`, `estados_idestados`, `marca_idmarca`, `prestadorservicos_idprestadorservico`) VALUES (:internalCode, :model, :serieNumber, :features, :obs,  :acquisitionDate, :patrimonialCode, :ipAdress, :lanPort, :user, :userDate, :activeEquipment, :location, :idCategory, :idState, :idBrand, :idProvider)");

        $sql->bindValue(':internalCode', $e->getInternalCode());
        $sql->bindValue(':model',$e->getModel());
        $sql->bindValue(':serieNumber',$e->getSerieNumber());
        $sql->bindValue(':features',$e->getFeatures());
        $sql->bindValue(':obs',$e->getObs());
        $sql->bindValue(':acquisitionDate',$e->getAcquisitionDate());
        $sql->bindValue(':patrimonialCode',$e->getPatrimonialCode());
        $sql->bindValue(':ipAdress',$e->getIpAdress());
        $sql->bindValue(':lanPort',$e->getLanPort());
        $sql->bindValue(':user',$e->getUser());
        $sql->bindValue(':userDate',$e->getUserDate());
        $sql->bindValue(':activeEquipment',$e->getActiveEquipment());
        $sql->bindValue(':location',$e->getLocation());

        $sql->bindValue(':idCategory',$e->getCategoryId());
        $sql->bindValue(':idState',$e->getStateId());
        $sql->bindValue(':idBrand',$e->getBrandId());
        $sql->bindValue(':idProvider',$e->getProviderId());

        $sql->execute();
    }

    public function getSpecificById($id) {
        $sql = $this->pdo->prepare(
            "SELECT equipamentos.*, prestadorServicos.nome, estados.estado, marca.nomeMarca, categoria.nomeCategoria FROM ((((equipamentos
            INNER JOIN estados ON equipamentos.estados_idestados = estados.idestados)
            INNER JOIN marca ON equipamentos.marca_idmarca = marca.idmarca)
            INNER JOIN categoria ON equipamentos.categoria_idcategoria = categoria.idcategoria)
            INNER JOIN prestadorServicos ON equipamentos.prestadorservicos_idprestadorservico = prestadorServicos.idprestadorServico)
            WHERE idequipamentos = :id"
        );

        $sql->bindValue(':id', $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            $eq = new equipments();

            $eq->setId($data['idEquipamentos']);
            $eq->setInternalCode($data['codInterno']);
            $eq->setSerieNumber($data['nSerie']);
            $eq->setFeatures(ucwords(strtolower($data['caracteristicas'])));
            $eq->setObs(ucwords(strtolower($data['observacoes'])));
            $eq->setPatrimonialCode($data['codPatrimonial']);
            $eq->setIpAdress($data['enderecoIp']);
            $eq->setAcquisitionDate($data['dataAdquisicao']);
            $eq->setLanPort($data['tomadaDeRede']);
            $eq->setUser($data['responsavel']);
            $eq->setUserDate($data['dataResponsavel']);
            $eq->setModel($data['modelo']);
            $eq->setLocation($data['localizacao']);
            $eq->setActiveEquipment($data['equipamentoAtivo']);

            $eq->setCategoryId($data['categoria_idCategoria']);
            $eq->setStateId($data['estados_idestados']);
            $eq->setBrandId($data['marca_idmarca']);
            $eq->setProviderId($data['prestadorServicos_idprestadorServico']);

            $eq->setCategoryName(ucwords(strtolower($data['nomeCategoria'])));
            $eq->setStateName(ucwords(strtolower($data['estado'])));
            $eq->setBrandName($data['nomeMarca']);
            $eq->setProviderName(ucwords(strtolower($data['nome'])));

            return $eq;
        }
        return;
    }

    public function updateEquipment(equipments $e) {
        $sql = $this->pdo->prepare(
        "UPDATE equipamentos 
        SET codInterno = :internalCode, modelo = :model, nSerie = :serieNumber, caracteristicas = :features, observacoes = :obs, dataAdquisicao = :acquisitionDate, codPatrimonial = :patrimonialCode, enderecoIp = :ipAdress, tomadaDeRede = :lanPort, responsavel = :user, dataResponsavel = :userDate, equipamentoAtivo = :activeEquipment, localizacao = :location, categoria_idCategoria = :categoryId, estados_idestados = :stateId, marca_idmarca = :brandId, prestadorServicos_idprestadorServico = :providerId 
        WHERE idequipamentos = :id"
        );

        $sql->bindValue(':id', $e->getId());
        $sql->bindValue(':internalCode', $e->getInternalCode());
        $sql->bindValue(':model', $e->getModel());
        $sql->bindValue(':serieNumber', $e->getSerieNumber());
        $sql->bindValue(':features', $e->getFeatures());
        $sql->bindValue(':obs', $e->getObs());
        $sql->bindValue(':acquisitionDate', $e->getAcquisitionDate());
        $sql->bindValue(':patrimonialCode', $e->getPatrimonialCode());
        $sql->bindValue(':ipAdress', $e->getIpAdress());
        $sql->bindValue(':lanPort', $e->getLanPort());
        $sql->bindValue(':user', $e->getUser());
        $sql->bindValue(':userDate', $e->getUserDate());
        $sql->bindValue(':activeEquipment', $e->getActiveEquipment());
        $sql->bindValue(':location', $e->getLocation());
        $sql->bindValue(':categoryId', $e->getCategoryId());
        $sql->bindValue(':stateId', $e->getStateId());
        $sql->bindValue(':brandId', $e->getBrandId());
        $sql->bindValue(':providerId', $e->getProviderId());

        $sql->execute();
    }
    
    public function deleteEquipment(equipments $e) {
        $sql = $this->pdo->prepare("DELETE FROM equipamentos WHERE idEquipamentos = :equipmentId");
        $sql->bindValue(':equipmentId', $e->getId());
        $sql->execute();
    }

    public function setEquipmentAsRetired(equipments $e, $categoryId) {
        $sql = $this->pdo->prepare("UPDATE equipamentos SET estados_idestados = :categoryId WHERE idEquipamentos = :equipmentId");
        $sql->bindValue(':categoryId', $categoryId);
        $sql->bindValue(':equipmentId', $e->getId());
        $sql->execute();
    }

    public function getAllNotLentEquipments() {
        $equipmentData = [];

        $sql = $this->pdo->prepare("SELECT equipamentos.idequipamentos, equipamentos.codInterno, equipamentos.enderecoip, categoria.nomecategoria FROM ((equipamentos 
        INNER JOIN categoria ON equipamentos.categoria_idCategoria = categoria.idcategoria) 
        INNER JOIN estados ON equipamentos.estados_idestados = estados.idestados) 
        WHERE estados.estado <> 'Emprestado';");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new equipments();

                $eq->setId($item['idequipamentos']);
                $eq->setInternalCode($item['codInterno']);
                $eq->setIpAdress($item['enderecoip']);
                $eq->setCategoryName(ucwords(strtolower($item['nomecategoria'])));

                $equipmentData[] =  $eq;
            }
            return $equipmentData;
        }
    }

    public function getAllLentEquipments() {
        $equipmentData = [];

        $sql = $this->pdo->prepare("SELECT equipamentos.idequipamentos, equipamentos.codInterno, equipamentos.enderecoip, categoria.nomecategoria FROM ((equipamentos 
        INNER JOIN categoria ON equipamentos.categoria_idCategoria = categoria.idcategoria) 
        INNER JOIN estados ON equipamentos.estados_idestados = estados.idestados) 
        WHERE estados.estado = 'Emprestado';");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new equipments();

                $eq->setId($item['idequipamentos']);
                $eq->setInternalCode($item['codInterno']);
                $eq->setIpAdress($item['enderecoip']);
                $eq->setCategoryName(ucwords(strtolower($item['nomecategoria'])));

                $equipmentData[] =  $eq;
            }
            return $equipmentData;
        }
    }

    public function getAllNotRetiredEquipaments() {
        $equipmentData = [];

        $sql = $this->pdo->prepare("SELECT equipamentos.idequipamentos, equipamentos.codInterno, equipamentos.enderecoip, categoria.nomecategoria FROM ((equipamentos 
        INNER JOIN categoria ON equipamentos.categoria_idCategoria = categoria.idcategoria) 
        INNER JOIN estados ON equipamentos.estados_idestados = estados.idestados) 
        WHERE estados.estado <> 'Abatido';");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new equipments();

                $eq->setId($item['idequipamentos']);
                $eq->setInternalCode($item['codInterno']);
                $eq->setIpAdress($item['enderecoip']);
                $eq->setCategoryName(ucwords(strtolower($item['nomecategoria'])));

                $equipmentData[] =  $eq;
            }
            return $equipmentData;
        }
    } 

    public function getIdByInternalCode($ic) {
        $sql = $this->pdo->prepare("SELECT idEquipamentos FROM equipamentos WHERE codInterno = :ci");
        $sql->bindValue(':ci', $ic);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return $data['idEquipamentos'];
        }
        return;
    }
}