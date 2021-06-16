DELIMITER $$
CREATE TRIGGER `returnLentEquipment` AFTER UPDATE ON `emprestimos` FOR EACH ROW BEGIN 
	DECLARE activeId INT;
    
    SELECT idestados INTO activeId FROM estados WHERE estado = 'Ativo';
    UPDATE equipamentos SET estados_idestados = activeId WHERE idEquipamentos = new.equipamentos_idEquipamentos;

END
$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `updateEquipmentStatusToLent` AFTER INSERT ON `emprestimos` FOR EACH ROW BEGIN
	DECLARE lentIdStatus INT;
    
    SELECT idestados INTO lentIdStatus FROM estados WHERE estado = "Emprestado";
    UPDATE equipamentos SET estados_idestados = lentIdStatus WHERE idequipamentos = new.equipamentos_idEquipamentos;
    
END
$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `setAssistanceDuration` AFTER INSERT ON `assistencia` FOR EACH ROW BEGIN
	DECLARE lentIdStatus INT;
    
    SELECT idestados INTO lentIdStatus FROM estados WHERE estado = "Emprestado";
    UPDATE equipamentos SET estados_idestados = lentIdStatus WHERE idequipamentos = new.equipamentos_idEquipamentos;
    
END
$$
DELIMITER ;