<?php require_once '../models/softwares.php';


class softwaresDAOMS implements sotfwaresDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function insertSoftware(softwares $s)
    {
        $sql = $this->pdo->prepare("INSERT INTO softwares(chave, versao, dataInicio, dataFinal, tipoSoftwares_idtipoSoftwares, prestadorServicos_idprestadorServico) VALUES (:key, :version, :initialDate, :finalDate, :typeId, :providerId)");

        $sql->bindValue(':key', $s->getKey());
        $sql->bindValue(':version', $s->getVersion());
        $sql->bindValue(':initialDate', $s->getInitialDate());
        $sql->bindValue(':finalDate', $s->getFinalDate());
        $sql->bindValue(':typeId', $s->getTypeId());
        $sql->bindValue(':providerId', $s->getProviderId());

        $sql->execute();
        return $this->pdo->lastInsertId();
    }

    public function updateSoftware(softwares $s) {
        $sql = $this->pdo->prepare("UPDATE softwares SET chave = :key, versao = :version, dataInicio = :initialDate, dataFinal = :finalDate, tipoSoftwares_idtipoSoftwares = :typeId, prestadorServicos_idprestadorServico = :providerId WHERE idsoftwares = :id");
        $sql->bindValue(':key', $s->getKey());
        $sql->bindValue(':version', $s->getVersion());
        $sql->bindValue(':initialDate', $s->getInitialDate());
        $sql->bindValue(':finalDate', $s->getFinalDate());
        $sql->bindValue(':typeId', $s->getTypeId());
        $sql->bindValue(':providerId', $s->getProviderId());
        $sql->bindValue(':id', $s->getId());
        $sql->execute();
    }

    public function deleteSoftware(softwares $s) {
        $sql = $this->pdo->prepare("DELETE FROM softwares WHERE idsoftwares = :softwareId");
        $sql->bindValue(':softwareId', $s->getId());
        $sql->execute();
    }

    public function getAllSoftwareTypes()
    {
        $softwaresType = [];

        $sql = $this->pdo->prepare("SELECT * FROM tiposoftwares");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $p = new softwares();

                $p->setTypeName($item['tipoSoftware']);

                $softwaresType[] =  $p;
            }
        }
        return $softwaresType;
    }

    public function getAllSoftwares()
    {
        $softwares = [];

        $sql = $this->pdo->prepare("SELECT softwares.*, tiposoftwares.tiposoftware, prestadorservicos.nome FROM ((softwares
        LEFT JOIN tiposoftwares ON tiposoftwares.idtiposoftwares = softwares.tiposoftwares_idtiposoftwares)
        LEFT JOIN prestadorservicos ON prestadorservicos.idprestadorservico = softwares.tiposoftwares_idtiposoftwares) ");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $s = new softwares();

                $s->setId($item['idsoftwares']);
                $s->setKey($item['chave']);
                $s->setVersion($item['versao']);
                $s->setInitialDate($item['dataInicio']);
                $s->setFinalDate($item['dataFinal']);
                $s->setTypeId($item['tipoSoftwares_idtipoSoftwares']);
                $s->setTypeName($item['tiposoftware']);
                $s->setProviderId($item['prestadorServicos_idprestadorServico']);
                $s->setProviderName($item['nome']);

                $softwares[] =  $s;
            }
        }
        return $softwares;
    }

    public function getSpecificSoftwareById($id) {
        $sql = $this->pdo->prepare(
            "SELECT softwares.*, tipoSoftwares.tipoSoftware, prestadorServicos.nome FROM ((softwares
            INNER JOIN tipoSoftwares ON tipoSoftwares.idtipoSoftwares = softwares.tipoSoftwares_idtipoSoftwares)
            INNER JOIn prestadorServicos ON prestadorServicos.idprestadorServico = softwares.tipoSoftwares_idtipoSoftwares) 
            WHERE idsoftwares = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            $s = new softwares();

            $s->setId($data['idsoftwares']);
            $s->setKey($data['chave']);
            $s->setVersion($data['versao']);
            $s->setInitialDate($data['dataInicio']);
            $s->setFinalDate($data['dataFinal']);
            $s->setTypeId($data['tipoSoftwares_idtipoSoftwares']);
            $s->setTypeName($data['tipoSoftware']);
            $s->setProviderId($data['prestadorServicos_idprestadorServico']);
            $s->setProviderName($data['nome']);

            return $s;
            
        }
        return ;
    }

    public function getSoftwareTypeIdByName($n) {
        $sql = $this->pdo->prepare("SELECT idtipoSoftwares FROM tiposoftwares WHERE tipoSoftware = :n");
        $sql->bindValue(':n', $n);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return $data['idtipoSoftwares'];    
        }
        return;
    }

    public function getEquipmentSoftwaresById($id) {
        $sql = $this->pdo->prepare("SELECT softwares.*, prestadorServicos");
    }

    public function createSoftwareType($t) {
        $sql = $this->pdo->prepare("INSERT INTO tiposoftwares(tipoSoftware) VALUES (:softwareType)");
        $sql->bindValue(':softwareType', $t);
        $sql->execute();
    }

    
    public function deleteSoftwareType($t) {
        $sql = $this->pdo->prepare('DELETE FROM tiposoftwares WHERE tipoSoftware = :softwareType');
        $sql->bindValue(':softwareType', $t);
        $sql->execute();
    }
}
