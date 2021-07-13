<?php 
require_once '../models/assistance.php';

class assistanceDAOMS implements assistanceDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function createAssistance(assistance $a)
    {
        $sql=$this->pdo->prepare("INSERT INTO assistencia(dataInicio, dataFim, duracao, descricao, objetivo, frontOffice, tipoOcorrencia_idtipoOcorrencia, equipamentos_idequipamentos, prestadorservicos_idprestadorServico) VALUE (:initialDate, :finalDate, :duration, :description, :goals, :frontOffice, :typeId, :equipmentId, :providerId)");
        $sql->bindValue(':initialDate', $a->getInitialDate());
        $sql->bindValue(':finalDate', $a->getFinalDate());
        $sql->bindValue(':duration', $a->getDuration());
        $sql->bindValue(':description', $a->getDescription());
        $sql->bindValue(':goals', $a->getGoals());
        $sql->bindValue(':frontOffice', $a->getFrontOffice());

        $sql->bindValue(':typeId', $a->getTypeId());
        $sql->bindValue(':equipmentId', $a->getEquipmentId());
        $sql->bindValue(':providerId', $a->getTechnicalId());
        $sql->execute();
    }

    public function deleteAssistance(assistance $a) {
        $sql = $this->pdo->prepare("DELETE FROM assistencia WHERE idAssistencia = :assistanceId");
        $sql->bindValue(':assistanceId', $a->getId());
        $sql->execute();
    }

    public function getAll() {
        $assistanceData = [];

        $sql = $this->pdo->prepare("SELECT * FROM ((assistencia INNER JOIN tipoocorrencia ON assistencia.tipoocorrencia_idtipoocorrencia = tipoocorrencia.idtipoocorrencia) INNER JOIN equipamentos ON assistencia.equipamentos_idEquipamentos = equipamentos.idEquipamentos) INNER JOIN prestadorservicos ON assistencia.prestadorservicos_idprestadorServico = prestadorservicos.idprestadorServico");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $a = new assistance();

                $a->setId($item['idAssistencia']);
                $a->setInitialDate($item['dataInicio']);
                $a->setFinalDate($item['dataFim']);
                $a->setDescription($item['descricao']);
                $a->setDuration($item['duracao']);
                $a->setTechnicalName($item['nome']);
                $a->setGoals($item['objetivo']);
                $a->setFrontOffice($item['frontOffice']);
                $a->setTypeId($item['idtipoOcorrencia']);
                $a->setTypeName($item['tipoOcorrencia']);
                $a->setEquipmentId($item['equipamentos_idEquipamentos']); 
                $a->setEquipmentName($item['codInterno']);         

                $assistanceData[] =  $a;
            } 
        }
        return $assistanceData;
    }

    public function getSpecific($id) {
        $sql = $this->pdo->prepare("SELECT * FROM ((assistencia INNER JOIN tipoocorrencia ON assistencia.tipoocorrencia_idtipoocorrencia = tipoocorrencia.idtipoocorrencia) INNER JOIN equipamentos ON assistencia.equipamentos_idEquipamentos = equipamentos.idEquipamentos) INNER JOIN prestadorservicos ON assistencia.prestadorservicos_idprestadorServico = prestadorservicos.idprestadorServico WHERE assistencia.idassistencia = :id");
        $sql->bindValue(':id',$id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            $a = new assistance();           

            $a->setId($data['idAssistencia']);
            $a->setInitialDate($data['dataInicio']);
            $a->setFinalDate($data['dataFim']);
            $a->setDescription($data['descricao']);
            $a->setDuration($data['duracao']);
            $a->setTechnicalName($data['nome']);
            $a->setGoals($data['objetivo']);
            $a->setFrontOffice($data['frontOffice']);
            $a->setTypeId($data['idtipoOcorrencia']);
            $a->setTypeName($data['tipoOcorrencia']);
            $a->setEquipmentId($data['equipamentos_idEquipamentos']);      
            
            return $a;
        }
        return;
    }

    public function getAssistanceByEquipmentId($eid) {
        $sql = $this->pdo->prepare("SELECT * FROM ((assistencia INNER JOIN tipoocorrencia ON assistencia.tipoocorrencia_idtipoocorrencia = tipoocorrencia.idtipoocorrencia) INNER JOIN equipamentos ON assistencia.equipamentos_idEquipamentos = equipamentos.idEquipamentos) INNER JOIN prestadorservicos ON assistencia.prestadorservicos_idprestadorServico = prestadorservicos.idprestadorServico WHERE assistencia.equipamentos_idEquipamentos = :eid");
        $sql->bindValue(':eid', $eid);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            $a = new assistance();
            $a->setId($data['idAssistencia']);
            $a->setInitialDate($data['dataInicio']);
            $a->setFinalDate($data['dataFim']);
            $a->setDescription($data['duracao']);
            $a->setDuration($data['descricao']);
            $a->setTechnicalName($data['nome']);
            $a->setGoals($data['objetivo']);
            $a->setFrontOffice($data['frontOffice']);
            $a->setTypeId($data['idtipoOcorrencia']);
            $a->setTypeName($data['tipoOcorrencia']);
            $a->setEquipmentId($data['equipamentos_idEquipamentos']);      
        
            return $a;
        }
        return;
    }

    public function getAssistanceTypeIdBYName($name)
    {
        $sql = $this->pdo->prepare("SELECT * FROM tipoocorrencia WHERE tipoocorrencia = :tipo");
        $sql->bindValue(':tipo', $name);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            return $data['idtipoOcorrencia'];
        }
        return;
    }

    public function getAllAssistanceTypes()
    {
        $assistanceTypes = [];

        $sql = $this->pdo->prepare("SELECT * FROM tipoocorrencia");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $a = new assistance();

                $a->setTypeId($item['idtipoOcorrencia']);
                $a->setTypeName($item['tipoOcorrencia']);

                $assistanceTypes[] =  $a;
            }
        }
        return $assistanceTypes;
    }

    public function updateAssistance(assistance $a) {
        $sql = $this->pdo->prepare('UPDATE assistencia SET dataInicio = :initialDate, dataFim = :finalDate, descricao = :description, objetivo = :goals, frontOffice = :frontOffice, tipoOcorrencia_idtipoOcorrencia = :typeId, equipamentos_idEquipamentos = :equipmentId, prestadorservicos_idprestadorServico = :providerId');
        $sql->bindValue(':initialDate',$a->getInitialDate());
        $sql->bindValue(':finalDate',$a->getFinalDate());
        $sql->bindValue(':description',$a->getDescription());
        $sql->bindValue(':goals',$a->getGoals());
        $sql->bindValue(':frontOffice',$a->getFrontOffice());
        $sql->bindValue(':typeId',$a->getTypeId());
        $sql->bindValue(':equipmentId',$a->getEquipmentId());
        $sql->bindValue(':providerId',$a->getTechnicalId());
        $sql->execute();
    }
}