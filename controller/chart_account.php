<?php
ob_start();
$message = "";
switch ($_REQUEST["ext"]) {
    case "put":
        try {
            $conteudo["description"] = $_REQUEST["description"];
            $setup->conn->insertQuery("chart_accounts", $conteudo);
            $message = "sucesso";
        }
        catch (Exception $e) {
            $message = $e->getMessage();
        }
    default:
        ob_get_clean();
        $setup->smarty->assign("message", $message);
        $estrutura[0] = array("C&oacute;digo", "Descri&ccedil;&atilde;o");
        $estrutura[1] = array("%[id_chart_account]%", "%[description]%");
        $tabela = $setup->conn->getTabela("SELECT id_chart_account, description FROM chart_accounts", $estrutura, "chart_accounts");
        $setup->smarty->assign("tabela", $tabela);
        $setup->pagina(true,$setup->smarty->fetch("chart_account.tpl"));
        break;
}
?>

