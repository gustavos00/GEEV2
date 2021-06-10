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
        $sql = $this->pdo->prepare("INSERT INTO assistencia(dataInicio, descricao, tecnico, objetivo, frontOffice, tipoOcorrencia_idtipoOcorrencia, equipamentos_idEquipamentos) VALUES (:initialDate, :description, :technical, :goals, :frontOffice, :assistanceTypeId, :equipmentId)");
        $sql->bindValue(':initialDate', $a->getInitialDate());
        $sql->bindValue(':description', $a->getDescription());
        $sql->bindValue(':technical', $a->getTechnical());
        $sql->bindValue(':goals', $a->getGoals());
        $sql->bindValue(':frontOffice', $a->getFrontOffice());
        $sql->bindValue(':assistanceTypeId', $a->getTypeId());
        $sql->bindValue(':equipmentId', $a->getEquipmentId());
        $sql->execute();
    }

    public function deleteAssistance(assistance $a) {
        $sql = $this->pdo->prepare("DELETE FROM assistencia WHERE idAssistencia = :assistanceId");
        $sql->bindValue(':assistanceId', $a->getId());
        $sql->execute();
    }

    public function getAll() {
        $assistanceData = [];

        $sql = $this->pdo->prepare("SELECT * FROM assistencia INNER JOIN tipoocorrencia ON assistencia.tipoocorrencia_idtipoocorrencia = tipoocorrencia.idtipoocorrencia");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $a = new assistance();

                $a->setId($item['idAssistencia']);
                $a->setInitialDate($item['dataInicio']);
                $a->setFinalDate($item['dataFim']);
                $a->setDescription($item['duracao']);
                $a->setDuration($item['descricao']);
                $a->setTechnical($item['tecnico']);
                $a->setGoals($item['objetivo']);
                $a->setFrontOffice($item['frontOffice']);
                $a->setTypeId($item['idtipoOcorrencia']);
                $a->setTypeName($item['tipoOcorrencia']);
                $a->setEquipmentId($item['equipamentos_idEquipamentos']);      

                $assistanceData[] =  $a;
            } 
        }
        return $assistanceData;
    }

    public function getAssistanceByEquipmentId($eid) {
        $sql = $this->pdo->prepare("SELECT * FROM assistencia INNER JOIN tipoOcorrencia ON assistencia.tipoOcorrencia_idtipoOcorrencia = tipoOcorrencia.idtipoOcorrencia WHERE assistencia.equipamentos_idEquipamentos = :eid");
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
            $a->setTechnical($data['tecnico']);
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
                $a->setTypeName(ucwords(strtolower($item['tipoOcorrencia'])));

                $assistanceTypes[] =  $a;
            }
        }
        return $assistanceTypes;
    }
}