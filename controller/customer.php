<?php
ob_start();
$message = "";
switch ($_REQUEST["ext"]) {
	case "json":
		$sql = "SELECT id_customer, name FROM customers ";

		if ($realQuery[1] == "live") {
			$sql .= "WHERE name LIKE '%".strtoupper($_REQUEST["arquivo"]) ."%'";
		}

		$result = array();

		$qy = $setup->conn->selectQuery($sql);
		$lin = $qy->getNumLinhas();
		for ($i = 0; $i < $lin; $i++) {
			$result[] = (object)array('id' => $qy->getReg("id_customer", $i),
					'value' => $qy->getReg("name", $i));
		}

		echo json_encode($result);
		break;
	case "put":
		try	{
			$conteudo["name"] = $_REQUEST["name"];
			$conteudo["document"] = $_REQUEST["document"];
			$conteudo["street"] = $_REQUEST["street"];
			$conteudo["zip_code"] = $_REQUEST["cep"];
			$conteudo["address_number"] = $_REQUEST["number"];
			$conteudo["city"] = $_REQUEST["city"];
			$conteudo["state"] = $_REQUEST["state"];
			$setup->conn->insertQuery("customers", $conteudo);
			$message = "sucesso";
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		ob_get_clean();
		$setup->smarty->assign("message", $message);
		$estrutura[0] = array("C&oacute;digo", "Nome", "Cidade");
		$estrutura[1] = array("%[id_customer]%", "%[name]%", "%[city]%");
		$tabela = $setup->conn->getTabela("SELECT id_customer, name, city FROM customers", $estrutura, "users");
		$setup->smarty->assign("tabela", $tabela);
		$setup->pagina(true,$setup->smarty->fetch("customer.tpl"));
		break;
}
?>
