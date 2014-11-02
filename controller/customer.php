<?php
ob_start();
$message = "";

$id = 0;
$name = "";
$fullname = "";
$document = "";
$street = "";
$zip_code = "";
$address_number = "";
$city = "";
$state = "";

switch ($_REQUEST["ext"]) {
	case "json":
		$sql = "SELECT id_customer, name FROM customers ";

		if ($realQuery[1] == "live") {
			$sql .= "WHERE UPPER(name) LIKE '%".strtoupper($_REQUEST["arquivo"]) ."%'
			or UPPER(fullname) LIKE '%".strtoupper($_REQUEST["arquivo"]) ."%'";
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
			$conteudo["fullname"] = $_REQUEST["fullname"];
			$conteudo["document"] = $_REQUEST["document"];
			$conteudo["street"] = $_REQUEST["street"];
			$conteudo["zip_code"] = $_REQUEST["cep"];
			$conteudo["address_number"] = $_REQUEST["number"];
			$conteudo["city"] = $_REQUEST["city"];
			$conteudo["state"] = $_REQUEST["state"];

			if ($_REQUEST["id"] == 0) {
				$setup->conn->insertQuery("customers", $conteudo);
			} else {
				$where["id_customer"] = htmlentities($_REQUEST["id"]);
				$setup->conn->updateQuery("customers", $conteudo, $where);
			}
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	case "get":
		if ($_REQUEST["id"] != 0) {
			$sql = "SELECT id_customer, name, fullname, document, street, zip_code, address_number, city, state FROM customers WHERE id_customer = " . htmlspecialchars($_REQUEST["id"]);
			$qy = $setup->conn->selectQuery($sql);
			$id = $qy->getReg("id_customer");
			$name = $qy->getReg("name");
			$fullname = $qy->getReg("fullname");
			$document = $qy->getReg("document");
			$street = $qy->getReg("street");
			$zip_code = $qy->getReg("zip_code");
			$address_number = $qy->getReg("address_number");
			$city = $qy->getReg("city");
			$state = $qy->getReg("state");
		}
		$show_page = true;
		break;
	default:
		$show_page = true;
		break;
}

if ($show_page) {
		ob_get_clean();
		$setup->smarty->assign("id_customer", $id);
		$setup->smarty->assign("name", $name);
		$setup->smarty->assign("fullname", $fullname);
		$setup->smarty->assign("document", $document);
		$setup->smarty->assign("street", $street);
		$setup->smarty->assign("zip_code", $zip_code);
		$setup->smarty->assign("address_number", $address_number);
		$setup->smarty->assign("city", $city);
		$setup->smarty->assign("state", $state);

		$estrutura[0] = array("C&oacute;digo", "Nome", "Cidade");
		$estrutura[1] = array("<a href=\"customer.get?id=%[id_customer]%\">%[id_customer]%</a>", "%[name]%", "%[city]%");
		$tabela = $setup->conn->getTabela("SELECT id_customer, name, city FROM customers", $estrutura, "customers");
		$setup->smarty->assign("tabela", $tabela);
		$setup->smarty->assign("message", $message);

		$setup->pagina(true,$setup->smarty->fetch("customer.tpl"));
}

?>
