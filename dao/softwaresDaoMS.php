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
        $sql = $this->pdo->prepare("INSERT INTO softwares(chave, versao, dataInicio, dataFinal, tipoSoftwares_idtipoSoftwares, prestadorservicos_idprestadorServico) VALUES (:key, :version, :initialDate, :finalDate, :typeId, :providerId)");

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
        $sql = $this->pdo->prepare("UPDATE softwares SET chave = :key, versao = :version, dataInicio = :initialDate, dataFinal = :finalDate, tipoSoftwares_idtipoSoftwares = :typeId, prestadorservicos_idprestadorServico = :providerId WHERE idsoftwares = :id");
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

    public function getSpecificSoftware($id)
    {
        $sql = $this->pdo->prepare("SELECT softwares.*, tiposoftwares.tiposoftware, prestadorservicos.* FROM ((softwares
        LEFT JOIN tiposoftwares ON tiposoftwares.idtiposoftwares = softwares.tiposoftwares_idtiposoftwares)
        LEFT JOIN prestadorservicos ON prestadorservicos.idprestadorservico = softwares.prestadorservicos_idprestadorservico)
        WHERE softwares.idsoftwares = :id");
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
            $s->setTypeName($data['tiposoftware']);
            $s->setProviderId($data['prestadorServicos_idprestadorServico']);
            $s->setProviderName($data['nome']);

            return $s;
        }
    }

    public function getSpecificSoftwareById($id) {
        $sql = $this->pdo->prepare(
            "SELECT softwares.*, tiposoftwares.tipoSoftware, prestadorservicos.nome FROM ((softwares
            LEFT JOIN tiposoftwares ON tiposoftwares.idtipoSoftwares = softwares.tipoSoftwares_idtipoSoftwares)
            LEFT JOIn prestadorservicos ON prestadorservicos.idprestadorServico = softwares.tipoSoftwares_idtipoSoftwares) 
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

    public function checkIfSoftwareTypeExists($n) {
        $sql = $this->pdo->prepare("SELECT * from tiposoftwares WHERE tiposoftware = :softwareType");
        $sql->bindValue(':softwareType', $n);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        }
        return false;
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

    public function getSpecificEquipmentSoftwares($id) {
        $softwares = [];

        $sql = $this->pdo->prepare("SELECT tiposoftwares.tipoSoftware, softwares.* FROM ((softwares
        LEFT JOIN softwares_has_equipamentos ON softwares_has_equipamentos.softwares_idsoftwares = softwares.idsoftwares)
        LEFT JOIN tiposoftwares ON tiposoftwares.idtipoSoftwares = softwares.tiposoftwares_idtipoSoftwares)
        WHERE softwares_has_equipamentos.equipamentos_idEquipamentos = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach($data as $item) {
                $s = new softwares();

                $s->setId($item['idsoftwares']);
                $s->setKey($item['chave']);
                $s->setVersion($item['versao']);
                $s->setInitialDate($item['dataInicio']);
                $s->setFinalDate($item['dataFinal']);
                $s->setTypeId($item['tipoSoftwares_idtipoSoftwares']);
                $s->setTypeName($item['tipoSoftware']);
                $s->setProviderId($item['prestadorServicos_idprestadorServico']);

                array_push($softwares, $s);
            }
        }
        return $softwares;
    }

    public function unlinkSoftwares($eqId, $softwaresIds) {
        $sql = $this->pdo->prepare("DELETE FROM softwares_has_equipamentos WHERE softwares_idsoftwares = :softwareId AND equipamentos_idEquipamentos = :eqId");
        $sql->bindValue(':eqId', $eqId);

        foreach($softwaresIds as $softwareId) {
            $sql->bindValue(':softwareId', $softwareId);
            $sql->execute();
        }
    }

    public function linkSoftwares($eqId, $softwaresIds) {
        $sql = $this->pdo->prepare("INSERT INTO softwares_has_equipamentos(softwares_idsoftwares, equipamentos_idEquipamentos) VALUES (:softId, :eqId);");
        $sql->bindValue(':eqId', $eqId);

        foreach($softwaresIds as $softwareId) {
            $sql->bindValue(':softId', $softwareId);
            $sql->execute();
        }
    }

    public function deleteAllSoftwares($contactIds) {
        $sql = $this->pdo->prepare("DELETE FROM contactos WHERE idcontactos = :contactId");

        foreach($contactIds as $contactId) {
            $sql->bindValue(':contactId', $contactId);
            $sql->execute();
        }
    }
}
