<?php require_once '../models/lent.php';

class lentDAOMS implements lentDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function createLent(lent $l)
    {
        $sql = $this->pdo->prepare("INSERT INTO emprestimos(responsavel, dataInicio, dataFim, contacto, observacoes, equipamentos_idEquipamentos) VALUES (:user, :initialDate, :finalDate, :contact, :obs, :idEquipment)");
        $sql->bindValue(':user', $l->getUser());
        $sql->bindValue(':initialDate', $l->getInitialDate());
        $sql->bindValue(':finalDate', $l->getFinalDate());
        $sql->bindValue(':contact', $l->getContact());
        $sql->bindValue(':obs', $l->getObs());
        $sql->bindValue(':idEquipment', $l->getEquipmentId());
        $sql->execute();
    }

    public function returnEquipment(lent $l, $stateId) {
        $sql = $this->pdo->prepare("UPDATE emprestimos SET dataFim = :finalDate WHERE equipamentos_idEquipamentos = :equipmentId");
        $sql->bindValue(':finalDate', $l->getFinalDate());
        $sql->bindValue(':equipmentId', $l->getEquipmentId());
        $sql->execute();

        $sql = $this->pdo->prepare("UPDATE equipamentos SET estados_idestados = :state WHERE idequipamentos = :equipmentId");
        $sql->bindValue(':state', $stateId);
        $sql->bindValue(':equipmentId', $l->getEquipmentId());
        $sql->execute();
    }

    public function getAll() {
        $lentData = [];

        $sql = $this->pdo->prepare("SELECT emprestimos.*, equipamentos.codInterno FROM (emprestimos 
        LEFT JOIN equipamentos ON emprestimos.equipamentos_idEquipamentos = equipamentos.idEquipamentos)");
        $sql->execute();
    
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new lent();

                $eq->setId($item['idemprestimos']);
                $eq->setUser($item['responsavel']);
                $eq->setInitialDate($item['dataInicio']);
                $eq->setFinalDate($item['dataFim']);
                $eq->setContact($item['contacto']);
                $eq->setObs($item['observacoes']);
            
                $eq->setEquipmentInternalCode($item['codInterno']);
                $eq->setEquipmentId($item['equipamentos_idEquipamentos']);

                $lentData[] =  $eq;
            }  
        }
        return $lentData;
    }

    public function getAllOpenLentProcess() {
        $lentData = [];

        $sql = $this->pdo->prepare("SELECT emprestimos.*, equipamentos.codInterno FROM (emprestimos 
        INNER JOIN equipamentos ON emprestimos.equipamentos_idEquipamentos = equipamentos.idEquipamentos) WHERE EXTRACT(year FROM emprestimos.dataFim) <> '0'");
        $sql->execute();
    
        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new lent();

                $eq->setId($item['idemprestimos']);
                $eq->setUser($item['responsavel']);
                $eq->setInitialDate($item['dataInicio']);
                $eq->setFinalDate($item['dataFim']);
                $eq->setContact($item['contacto']);
                $eq->setObs($item['observacoes']);
            
                $eq->setEquipmentInternalCode($item['codInterno']);
                $eq->setEquipmentId($item['equipamentos_idEquipamentos']);

                $equipmentData[] =  $eq;
            }
            return $equipmentData;
        }
    }

    public function checkIfIslent($id)
    {
        $sql=$this->pdo->prepare("SELECT codInterno FROM (equipamentos 
        INNER JOIN estados ON equipamentos.estados_idestados = estados.idestados)
        WHERE estados.estado = 'Emprestado' AND equipamentos.idEquipamentos = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();


        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return true;
        }
        return false;
    }
        
    public function deleteLentProcess($id) {
        $sql = $this->pdo->prepare("DELETE FROM emprestimos WHERE idemprestimos = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();
    }
}
