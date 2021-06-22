<?php require_once '../models/providers.php';


class providersDAOMS implements providersDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function getAll()
    {
        $providerData = [];

        $sql = $this->pdo->prepare("SELECT * FROM prestadorservicos");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $p = new provider();

                $p->setId($item['idprestadorServico']);
                $p->setName($item['nome']);
                $p->setObs($item['observacoes']);

                $providerData[] =  $p;
            }
        }
        return $providerData;
    }

    public function getSpecific($id) {
        $sql = $this->pdo->prepare("SELECT * FROM prestadorservicos WHERE idprestadorservico = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            $p = new provider();

            $p->setId($data['idprestadorservico']);
            $p->setName($data['nome']);
            $p->setObs($data['observacoes']);

            return $p;
            
        }
        return;
    }

    public function getIdByName($n)
    {
        $sql = $this->pdo->prepare("SELECT * FROM prestadorservicos WHERE nome = :nome");
        $sql->bindValue(':nome', $n);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return $data['idprestadorServico'];
        }
        return;
    }

    public function getAllContactsType() {
        $contactsType = [];
        $sql = $this->pdo->prepare("SELECT * from tipocontacto");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach($data as $item) {
                $p = new provider();
                $p->setContactType($item['tipo']);

                $contactsType[] = $p;
            }   
            return $contactsType;
        }
    }

    public function createContact(provider $p) {
        $sql = $this->pdo->prepare("INSERT INTO contactos(contacto, tipoContacto_idtipoContacto) VALUES (:contact, :contactTypeId)");
        $sql->bindValue(':contact', $p->getContact());
        $sql->bindValue(':contactTypeId', $p->getContactTypeId());
        $sql->execute();

        return $this->pdo->lastInsertId();
    }

    public function createProvider(provider $p) {
        $sql = $this->pdo->prepare("INSERT INTO prestadorservicos(nome, observacoes) VALUES (:name, :obs);");
        $sql->bindValue(':name', $p->getName());
        $sql->bindValue(':obs', $p->getObs());
        $sql->execute();

        return $this->pdo->lastInsertId();
    }

    public function linkProviderToContacts($providerId, $contactsIds) {
        $data = "INSERT INTO prestadorservicos_has_contactos(prestadorservicos_idprestadorServico, contactos_idcontactos) VALUES ";

        for($i = 0; $i < count($contactsIds); $i++) {  
            if ($i != count($contactsIds) - 1) {
                $data .= "({$providerId}, {$contactsIds[$i]}), ";
            } else {
                $data .= "({$providerId}, {$contactsIds[$i]}); ";
            }
        }


        echo $data;
        $sql = $this->pdo->prepare($data);
        $sql->execute();
        
    }

    public function getContactTypeIdByName($n) {
        $sql = $this->pdo->prepare("SELECT idtipoContacto FROM tipocontacto WHERE tipo = :type");
        $sql->bindValue(':type', $n);
        $sql->execute();

        if($sql->rowCount() > 0) {
            $item = $sql->fetch(\PDO::FETCH_ASSOC);

            return $item['idtipoContacto'];
        }
        return;
    }
}
