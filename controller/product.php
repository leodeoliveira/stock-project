<?php
ob_start();
$message = "";
$show_page = false;

$id = 0;
$description = "";
$unit_value = "";
$note = "";

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
			$conteudo["description"] = $_REQUEST["description"];
			$conteudo["unit_value"] =  (float) str_replace(",", ".", $_REQUEST["unit_value"]);
			$conteudo["note"] = $_REQUEST["note"];

			if ($_REQUEST["id"] == 0) {
				$setup->conn->insertQuery("products", $conteudo);
			} else {
				$where["id_product"] = htmlentities($_REQUEST["id"]);
				$setup->conn->updateQuery("products", $conteudo, $where);
			}
			$message = "sucesso" . $_REQUEST["description"];
		}
		catch (Exception $e) {
			$message = $e->getMessage();
		}
		$show_page = true;
		break;
	case "get":
		if ($_REQUEST["id"] != 0) {
			$sql = "SELECT id_product, description, unit_value, note FROM products WHERE id_product = " . htmlspecialchars($_REQUEST["id"]);
			$qy = $setup->conn->selectQuery($sql);
			$id = $qy->getReg("id_product");
			$description = $qy->getReg("description");
			$unit_value = $qy->getReg("unit_value");
			$note = $qy->getReg("note");
		}
		$show_page = true;
		break;
	default:
		$show_page = true;
		break;
}

if ($show_page) {
	ob_get_clean();
	$setup->smarty->assign("message", $message);
	$setup->smarty->assign("id_product", $id);
	$setup->smarty->assign("description", $description);
	$setup->smarty->assign("unit_value", $unit_value);
	$setup->smarty->assign("note", $note);

	$estrutura[0] = array("C&oacute;digo", "Descri&ccedil;&atilde;o", "Valor unit&aacute;rio");
	$estrutura[1] = array("<a href=\"product.get?id=%[id_product]%\">%[id_product]%</a>", "%[description]%", "%[unit_value]%");
	$tabela = $setup->conn->getTabela("SELECT id_product, description, unit_value FROM products", $estrutura, "products");
	$setup->smarty->assign("tabela", $tabela);
	$setup->pagina(true,$setup->smarty->fetch("product.tpl"));
}
?>
