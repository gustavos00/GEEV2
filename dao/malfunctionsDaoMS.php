<?php require_once '../models/malfunctions.php';

class malfunctionsDAOMS implements malfunctionsDAO
{
    private $pdo;

    public function __construct(PDO $driver)
    {
        $this->pdo = $driver;
    }

    public function getAll() {
        $malfunctionData = [];

        $sql = $this->pdo->prepare("SELECT avarias.*, prestadorservicos.* FROM avarias INNER JOIN prestadorservicos ON prestadorservicos_idprestadorservico = prestadorservicos.idprestadorservico");
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($data as $item) {
                $mf = new malfunction();
                
                $mf->setId($item['idavarias']);
                $mf->setDate($item['dataAvaria']);
                $mf->setDescription($item['descricao']);
                $mf->setAssistanceId($item['assistencia_idAssistencia']);
                $mf->setProviderName($item['nome']);
                $mf->setProviderId($item['idprestadorServico']);

                $malfunctionData[] = $mf;
            }
        }
        return $malfunctionData;
    }

    public function createMalfunction(malfunction $mf) {
        $sql = $this->pdo->prepare("INSERT INTO avarias(dataAvaria, descricao, assistencia_idAssistencia, prestadorServicos_idprestadorServico) VALUES (:date, :description, :assistanceId, :providerId);");
        $sql->bindValue(':date', $mf->getDate());
        $sql->bindValue(':description', $mf->getDescription());
        $sql->bindValue(':assistanceId', $mf->getAssistanceId());
        $sql->bindValue(':providerId', $mf->getProviderId());
        $sql->execute();
    }

    public function deleteMalfunction(malfunction $mf) {
        $sql = $this->pdo->prepare("DELETE FROM avarias WHERE idAvarias = :malfunctionId");
        $sql->bindValue(':malfunctionId', $mf->getId());
        $sql->execute();
    }
}