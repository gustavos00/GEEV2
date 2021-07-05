<?php require_once '../models/brands.php';

class brandsDAOMS implements brandsDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function getAll()
    {
        $brandData = [];

        $sql = $this->pdo->prepare("SELECT * FROM marca");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $b = new brand();

                $b->setId($item['idmarca']);
                $b->setBrandName($item['nomeMarca']);

                $brandData[] =  $b;
            }
        }
        return $brandData;
    }

    public function getIdByName($n)
    {
        $sql = $this->pdo->prepare("SELECT * FROM marca WHERE nomeMarca = :nome");
        $sql->bindValue(':nome', $n);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            $b = new brand();

            $b->setId($data['idmarca']);
            $b->setBrandName($data['nomeMarca']);

            return $data['idmarca'];
        }
        return;
    }

    public function createBrand(brand $b)
    {
        $sql = $this->pdo->prepare('INSERT INTO marca(nomeMarca) VALUES (:brandName)');
        $sql->bindValue(':brandName', $b->getBrandName());
        $sql->execute();
    }

    public function deleteBrand(brand $b)
    {
        $sql = $this->pdo->prepare('DELETE FROM marca WHERE nomeMarca = :brandName');
        $sql->bindValue(':brandName', $b->getBrandName());
        $sql->execute();
    }

    public function checkIfExist($n) {
        $sql = $this->pdo->prepare("SELECT * FROM marca WHERE nomeMarca = :brandName");
        $sql->bindValue(':brandName', $n);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
