<?php require_once '../models/categorys.php';

class categorysDAOMS implements categorysDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function getAll()
    {
        $categoryData = [];

        $sql = $this->pdo->prepare("SELECT * FROM categoria");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $eq = new category();

                $eq->setId($item['idCategoria']);
                $eq->setCategoryName(ucwords(strtolower($item['nomeCategoria'])));
                $eq->setCategoryCode($item['codCategoria']);

                $categoryData[] =  $eq;
            }
        }
        return $categoryData;
    }

    public function getIdByName($n)
    {
        $sql = $this->pdo->prepare("SELECT * FROM categoria WHERE nomeCategoria = :nome");
        $sql->bindValue(':nome', strtoupper($n));
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);

            $c = new category();

            $c->setId($data['idCategoria']);
            $c->setCategoryName($data['nomeCategoria']);
            $c->setCategoryCode($data['codCategoria']);

            return $data['idCategoria'];
        }
        return;
    }

    public function createCategory(category $c)
    {
        $sql = $this->pdo->prepare("INSERT INTO categoria(nomeCategoria,codCategoria) VALUES (:categoryName, :categoryCode)");
        $sql->bindValue(':categoryName', $c->getCategoryName());
        $sql->bindValue(':categoryCode', $c->getCategoryCode());
        $sql->execute();
    }

    public function getRetiredCategoryId() {
        $sql = $this->pdo->prepare("SELECT `idestados` FROM `estados` WHERE `estado` = 'Abatido'");
        $sql->execute();

        if($sql->rowCount() > 0) {
            $data = $sql->fetch(\PDO::FETCH_ASSOC);
            return $data['idestados'];
        }
        return ;
    }

    public function deleteCategory(category $c) {
        $sql = $this->pdo->prepare("DELETE FROM categoria WHERE nomeCategoria = :categoryName");
        $sql->bindValue(':categoryName', $c->getName());
        $sql->execute();
    }

    public function checkIfExist($n) {
        $sql = $this->pdo->prepare("SELECT * FROM categoria WHERE nomeCategoria = :categoryName");
        $sql->bindValue(':categoryName', $n);
        $sql->execute();

        if($sql->rowCount() > 0) {
            return true;
        }
        return false;
    }
}
