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

            $s = new brand();

            $s->setId($data['idestados']);
            $s->setBrandName($data['estado']);

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

    public function checkIfExist($n) {
        $sql = $this->pdo->prepare("SELECT * FROM estados WHERE estado = :stateName");
        $sql->bindValue(':stateName', $n);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
