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
        return $this->pdo->lastInsertId();
    }

    public function getSpecificById($id) {
        $sql = $this->pdo->prepare(
            "SELECT equipamentos.*, prestadorservicos.nome, estados.estado, marca.nomeMarca, categoria.nomeCategoria FROM ((((equipamentos
            INNER JOIN estados ON equipamentos.estados_idestados = estados.idestados)
            INNER JOIN marca ON equipamentos.marca_idmarca = marca.idmarca)
            INNER JOIN categoria ON equipamentos.categoria_idcategoria = categoria.idcategoria)
            INNER JOIN prestadorservicos ON equipamentos.prestadorservicos_idprestadorservico = prestadorservicos.idprestadorServico)
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

    public function updateEquipment(equipments $e, $internalCodeStatus, $serieNumberStatus, $ipStatus) {
        $query = "UPDATE equipamentos 
        SET " . (($internalCodeStatus == 'd') ? "codInterno = :internalCode," : "") . "modelo = :model, " . (($serieNumberStatus == 'd') ? "nSerie = :serieNumber," : "") . " caracteristicas = :features, observacoes = :obs, dataAdquisicao = :acquisitionDate, codPatrimonial = :patrimonialCode, " . (($ipStatus == 'd') ? "enderecoIp = :ipAdress," : "") . " tomadaDeRede = :lanPort, responsavel = :user, dataResponsavel = :userDate, equipamentoAtivo = :activeEquipment, localizacao = :location, categoria_idCategoria = :categoryId, estados_idestados = :stateId, marca_idmarca = :brandId, prestadorServicos_idprestadorServico = :providerId 
        WHERE idequipamentos = :id";

        $sql = $this->pdo->prepare($query);

        $sql->bindValue(':id', $e->getId());
        $sql->bindValue(':model', $e->getModel());
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

        ($internalCodeStatus == 'd') ? $sql->bindValue(':internalCode', $e->getInternalCode()) : null;
        ($serieNumberStatus == 'd') ? $sql->bindValue(':serieNumber', $e->getSerieNumber()) : null;
        ($internalCodeStatus == 'd') ? $sql->bindValue(':ipAdress', $e->getIpAdress()) : null;
        
        $sql->execute();
    }
    
    public function deleteEquipment(equipments $e) {
        try {
            $sql = $this->pdo->prepare("DELETE FROM equipamentos WHERE idEquipamentos = :equipmentId");
            $sql->bindValue(':equipmentId', $e->getId());
            $sql->execute();
        } catch (Exception $e) {
            return 'error';
        }

    }

    public function setAsLent($id, $cid) {
        try {
            $sql = $this->pdo->prepare("UPDATE equipamentos SET estados_idestados = :categoryId WHERE idEquipamentos = :id");
            $sql->bindValue(':id', $id);
            $sql->bindValue(':categoryId', $cid);
            $sql->execute();
        } catch (Exception $e) {
            return 'error';
        }

    }

    public function setMalfunction($id, $mid) {
        try {
            $sql = $this->pdo->prepare("UPDATE equipamentos SET avarias_idavarias = :malfunctionId WHERE idEquipamentos = :id");
            $sql->bindValue(':id', $id);
            $sql->bindValue(':malfunctionId', $mid);
            $sql->execute();
        } catch (Exception $e) {
            return 'error';
        }

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
        }
        return $equipmentData;
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

    public function getIpStatus($ip) {
        $sql = $this->pdo->prepare("SELECT idEquipamentos from equipamentos WHERE enderecoip = :ip");
        $sql->bindValue(':ip', $ip);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        }
        
        return false;
    }

    public function getInternalCodeStatus($ic) {
        $sql = $this->pdo->prepare("SELECT codInterno from equipamentos WHERE codInterno = :ic");
        $sql->bindValue(':ic', $ic);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return ($data['codInterno'] == $ic); //Se o novo for igual ao antigo
        }
        
        return false;
    }

    public function getSerieNumberStatus($ns) {
        $sql = $this->pdo->prepare("SELECT idEquipamentos from equipamentos WHERE nSerie = :ns");
        $sql->bindValue(':ns', $ns);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        }
        
        return false;
    }

    public function linkSoftwares($softwareId, $equipmentId) {
        $sql = $this->pdo->prepare("INSERT INTO softwares_has_equipamentos(softwares_idsoftwares, equipamentos_idequipamentos) VALUES (:softwareId, :equipmentId)");
        $sql->bindValue(':softwareId', $softwareId);
        $sql->bindValue(':equipmentId', $equipmentId);
        $sql->execute();
    }

    public function getHistoric($id) {
        $equipmentData = [];
        $sql = $this->pdo->prepare("SELECT * FROM historico WHERE equipamentos_idEquipamentos = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0 ) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new equipments();

                $eq->setUser($item['entidade']);
                $eq->setInitialDate($item['dataInicio']);
                $eq->setFinalDate($item['dataFim']);

                $equipmentData[] =  $eq;
            }
        }
        return $equipmentData;
    }

    public function deleteHistoric(equipments $e) {
        $sql = $this->pdo->prepare("DELETE FROM historico WHERE equipamentos_idEquipamentos = :id");
        $sql->bindValue(':id', $e->getId());
        $sql->execute();
    }
}