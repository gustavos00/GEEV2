<?php require_once './models/user.php';

class usersDAOMS implements usersDAO {
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function insertData(user $u) {
        $sql = $this->pdo->prepare("INSERT INTO user(enderecoIp, token) VALUES (:ip, :token);");
        $sql->bindValue(':ip',$u->getIp());
        $sql->bindValue(':token',$u->getToken());
        $sql->execute();
    }
}