<?php require_once '../models/states.php';

class statesDAOMS implements statesDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function getAll()
    {
        $stateData = [];

        $sql = $this->pdo->prepare("SELECT * FROM estados");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $s = new state();

                $s->setId($item['idestados']);
                $s->setState($item['estado']);

                $stateData[] =  $s;
            }
        }
        return $stateData;
    }

    public function getIdByName($n)
    {
        $sql = $this->pdo->prepare("SELECT * FROM estados WHERE estado = :nome");
        $sql->bindValue(':nome', $n);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            return $data['idestados'];
        }
        return;
    }

    public function createState(state $s)
    {
        $sql = $this->pdo->prepare("INSERT INTO estados(estado) VALUES (:state);");
        $sql->bindValue(':state', $s->getState());
        $sql->execute();
    }

    public function deleteState(state $s)
    {
        $sql = $this->pdo->prepare("DELETE FROM estados WHERE estado = :state");
        $sql->bindValue(':state', $s->getState());
        $sql->execute();
    }

    public function createLendState() {
        $sql = $this->pdo->prepare("INSERT INTO estados(estado) VALUES ('Emprestado')");
        $sql->execute();

        return $this->pdo->lastInsertId();
    }

    public function createActiveState() {
        $sql = $this->pdo->prepare("INSERT INTO estados(estado) VALUES ('Ativo')");
        $sql->execute();

        return $this->pdo->lastInsertId();
    }

    public function checkIfExist($n) {
        $sql = $this->pdo->prepare("SELECT * FROM estados WHERE estado = :stateName");
        $sql->bindValue(':stateName', $n);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        }
        return false;
    }

    public function getRetiredStateId() {
        $sql = $this->pdo->prepare("SELECT `idestados` FROM `estados` WHERE `estado` = 'Abatido'");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return $data['idestados'];
        }
        return false;
    }

    public function getActiveStateId() {
        $sql = $this->pdo->prepare("SELECT `idestados` FROM `estados` WHERE `estado` = 'Ativo'");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return $data['idestados'];
        }
        return false;
    }
}
