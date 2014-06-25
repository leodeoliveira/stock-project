<?php
ob_start();
$message = "";
switch ($_REQUEST["ext"]) {
	case "json":
		$sql = "SELECT id_product, description FROM products ";

		if ($realQuery[1] == "live") {
			$sql .= "WHERE description LIKE '%".strtoupper($_REQUEST["arquivo"]) ."%'";
		}

		$result = array();

		$qy = $setup->conn->selectQuery($sql);
		$lin = $qy->getNumLinhas();
		for ($i = 0; $i < $lin; $i++) {
			$result[] = (object)array('id' => $qy->getReg("id_product", $i),
					'value' => $qy->getReg("description", $i));
		}

		echo json_encode($result);
		break;
	case "put":
		try	{
			$conteudo["description"] = htmlentities($_REQUEST["description"]);
			$conteudo["unit_value"] = $_REQUEST["unit_value"];
			$conteudo["note"] = $_REQUEST["note"];
			$setup->conn->insertQuery("products", $conteudo);
			$message = "sucesso";
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
	default:
		ob_get_clean();
		$setup->smarty->assign("message", $message);
		$estrutura[0] = array("C&oacute;digo", "Descri&ccedil;&atilde;o", "Valor unit&aacute;rio");
		$estrutura[1] = array("%[id_product]%", "%[description]%", "%[unit_value]%");
		$tabela = $setup->conn->getTabela("SELECT id_product, description, unit_value FROM products", $estrutura, "users");
		$setup->smarty->assign("tabela", $tabela);
		$setup->pagina(true,$setup->smarty->fetch("product.tpl"));
		break;
}
?>