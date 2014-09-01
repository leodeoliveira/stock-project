<?php
error_reporting(E_ALL);

if (0 > version_compare(PHP_VERSION, '5')) {
	die('This file was generated for PHP 5');
}

require_once ('class.MyQuery.php');
require_once ('class.Util.php');
require_once ('class.UI.php');

/**
 * Classe para fazer as operações com o banco de dados.
 *
 * Esta classe faz as opereações de insert, update e delete
 * usando banco de dados Firebird/Interbase.
 *
 * @access public
 * @author Anderson Jordão Marques
 * @version 2.0
 * @copyright Copyright &copy; 2007-2008, Portal Urbanauta LTDA
 */
class MyDataBase {

	private $host = null;
	//private $base = null;
	private $user = null;
	private $pass = null;

	/**
	 * @var mysqli
	 */
	private $conn = null;
	private $smarty = null;
	private $util = null;

	// --- OPERATIONS ---

	function MyDataBase($host,$base,$user,$pass,&$smarty) {
    	$this->conn = mysqli_connect($host, $user, $pass, $base);
		$this->host = $host;
		//$this->base = mysql_select_db($base, $this->conn);
		$this->user = $user;
		$this->pass = $pass;
		$this->smarty = &$smarty;
		$this->util = new Util();
	}

	/**
	 * Função para retornar a ID da conexão.
	 *
	 * @access public
	 * @return ibase_id
	 * @since 12/03/2007
	 * @version 1.0
	 */
	function getId() {
		return $this->conn;
	}

	function getLastInsertId() {
		return $this->conn->insert_id;
	}

	/**
	 * Função para execução de Queries do tipo SELECT
	 *
	 * Esta função recebe como parametros a instrução e
	 * se existe ou não paginação.
	 * @access public
	 * @param string $sql String contendo o valor da consulta.
	 * @param int $pagg nº de registros por página, 0 para sem paginação.
	 * @return MyQuery
	 * @since 19/07/2005
	 * @version 1.0
	 */
	function selectQuery($sql, $pagg=0) {
		if ($pagg != 0) {
			if (isset($pagg["registros"])) {
				$sql .= " LIMIT ". $pagg["registros"]*($pagg["pagina"]) .",".$pagg["registros"];
				$page = $pagg["pagina"]+1;
				$pagg = $pagg["registros"];
			} else {
				$sql .= "LIMIT 0,".$pagg;
				$page = 1;
			}
		} else $page=0;
		//tirar echo $sql;
		$data = new MyQuery(mysqli_query($this->conn, $sql),$pagg,$page);
		return $data;
		unset($data);
	}

	/**
	 * Função para execução de Queries do tipo INSERT.
	 *
	 * @param string $tabela Tabela onde será inserido os dados.
	 * @param array $conteudo Vetor com o conteúdo. Onde a chave representa o campo do banco.
	 * @return void
	 */
	function insertQuery($tabela,$conteudo) {
		$sql = "INSERT INTO $tabela ";
		$sql_select = "";
		$sql_blob = NULL;
		$sql_insert = "(";
		$sql_values = "(";
		foreach ($conteudo as $campo => $valor) $sql_select .= "$campo,";
		$result = mysqli_query($this->conn, "SELECT ".substr($sql_select,0,-1)." FROM $tabela LIMIT 1");
		$data = new MyQuery($result,0,0);
		foreach ($conteudo as $campo => $valor) {
			$info = $data->getColInfo($campo);

			switch ($info['tp']) {
				case "BLOB":
					if ($valor != "") {
						$sql_blob[] = $this->strToMemo($valor);
						$sql_insert .= "$campo, ";
						$sql_values .= " ? , ";
					}
					break;
				case "TIMESTAMP":
					if ($valor != "") {
						$sql_insert .= "$campo,";
						$sql_values .= "'".$this->strToDate($valor)."',";
					}
					break;
				case "TIME":
					if ($valor != "") {
						$sql_insert .= "$campo,";
						$sql_values .= "'".strToUpper($valor)."',";
					}
					break;
				case "INTEGER":
				case "SMALLINT":
				case "NUMERIC":
					if ($valor != "") {
						$sql_insert .= "$campo,";
						$sql_values .= strToUpper($valor).",";
					}
					break;
				case "FLOAT":
				case "DOUBLE":
				case "DOUBLE PRECISION":
					if ($valor != "") {
						$sql_insert .= "$campo,";
						$sql_values .= "'".str_replace(",",".",$valor)."',";
					}
					break;
				default:
					if (substr($valor,0,7) == "(SELECT" ||
					strToUpper(substr($valor,0,9)) == "CASE WHEN") {
						$sql_insert .= "$campo,";
						$sql_values .= $valor.",";
					} elseif ($valor != "") {
						$sql_insert .= "$campo,";
						$sql_values .= "'".$valor."',";
					}
					break;
			}
		}
		unset($data);
		$sql_insert = substr($sql_insert,0,-1).")";
		$sql_values = substr($sql_values,0,-1).")";
		$sql .= $sql_insert . " VALUES " . $sql_values;
		//tirar echo $sql;
		$blobids = NULL;
		if ($sql_blob != NULL) {
			for ($u=0;$u < count($sql_blob);$u++) {
				$blobids .= '$sql_blob['.$u.'], ';
			}
			$blobids = substr($blobids,0,-2);
			eval("mysqli_query(\$this->conn, \$sql, $blobids);");
		} else mysqli_query($this->conn, $sql);
	}

	/**
	 * Função para execução de Queries do tipo DELETE FROM
	 *
	 * @access public
	 * @param string $tabela Tabela onde serão removido os dados.
	 * @param array $onde Cláusula WHERE da consulta. A chave do array representa o campo do banco.
	 * @return void
	 */
	function deleteQuery($tabela,$onde) {
		$sql = "DELETE FROM $tabela WHERE ";
		foreach ($onde as $campo => $valor) $sql .= "$campo = '$valor' AND ";
		$sql = substr($sql,0,-5);
		mysqli_query($sql,$this->conn);
	}

	/**
	 * Função para execução de Queries do tipo UPDATE SET
	 *
	 * @access public
	 * @param string $tabela Tabela onde será inserido os dados.
	 * @param array $conteudo Vetor com o conteúdo. Onde a chave representa o campo do banco.
	 * @param array $onde Cláusula WHERE da consulta. A chave do array representa o campo do banco.
	 * @return void
	 */
	function updateQuery($tabela,$conteudo,$onde) {
		$sql = "UPDATE $tabela SET ";
		$sql_select = "";
		$sql_blob = NULL;
		$sql_update = "";
		foreach ($conteudo as $campo => $valor) $sql_select .= "$campo,";
		$sql_select = "SELECT ".substr($sql_select,0,-1)." FROM " . $tabela . " LIMIT 1";
		$data = new MyQuery(mysqli_query($this->conn,$sql_select),0,0);
		foreach ($conteudo as $campo => $valor) {
			$info = $data->getColInfo($campo);
			switch ($info['tp']) {
				case "BLOB":
					if ($valor != "") {
						$sql_blob[] = $this->strToMemo($valor);
						$sql_update .= "$campo = ? ,";
					}
					break;
				case "TIMESTAMP":
					if ($valor != "") {
						$sql_update .= "$campo = '".$this->strToDate($valor)."',";
					}
					break;
				case "DATE":
				case "TIME":
					if ($valor != "") {
						$sql_update .= "$campo = '".$valor."',";
					}
					break;
				case "INTEGER":
				case "SMALLINT":
				case "NUMERIC":
					if ($valor != "") $sql_update .= "$campo = $valor,";
					break;
				case "FLOAT":
				case "DOUBLE":
				case "DOUBLE PRECISION":
					if ($valor != "") {
						$sql_update .= "$campo = '".str_replace(",",".",$valor)."',";
					}
					break;

				default:
					if (substr($valor,0,7) == "(SELECT" ||
					strToUpper(substr($valor,0,6)) == "GEN_ID" ||
					strToUpper(substr($valor,0,9)) == "CASE WHEN") {
						$sql_update .= "$campo = ".strToUpper($valor).",";
					} elseif ($campo=="CD_SENHA_MASTER") {
						$sql_update .= "$campo='".$valor."',";
					} elseif (strtoupper(trim($valor)) == "NULL") {
						$sql_update .= "$campo=NULL,";
					} elseif ($valor == " ") {
						$sql_update .= "$campo='',";
					} elseif ($valor != "") {
						$sql_update .= "$campo='".$valor."',";
					}
					break;
			}
		}
		unset($data);
		$sql_update = substr($sql_update,0,-1);
		$sql .= $sql_update . " WHERE ";
		if (is_array($onde)) {
			foreach ($onde as $campo => $valor) {
				if (strtoupper(trim($valor)) == "NULL") $sql .= "$campo IS NULL AND ";
				elseif (strtoupper(trim($valor)) == "NOT NULL") $sql .= "$campo IS NOT NULL AND ";
				else $sql .= "$campo = '$valor' AND ";
			}
			$sql = substr($sql,0,-5);
		}
		elseif(is_string($onde)) $sql .= $onde;
		//tirar echo $sql;
		$blobids = NULL;
		if ($sql_blob != NULL) {
			for ($u=0;$u < count($sql_blob);$u++) {
				$blobids .= '$sql_blob['.$u.'], ';
			}
			$blobids = substr($blobids,0,-2);
			eval("mysqli_query(\$this->conn, \$sql, $blobids);");
		} else mysqli_query($this->conn, $sql);
		//echo $sql;
	}

	/**
	 * Função para execução de Procedures.
	 *
	 * Execute as procedures com os parametros passados.
	 * @access public
	 * @param string $procedure Nome da procedure a ser executada.
	 * @param string $parametros Os parametros para executar a procedure.
	 * Podem ser dividos em um array, ou passados como string divididos por " | "
	 * @return Query
	 */
	function executeProcedure($procedure,$parametros) {
		$sql = "EXECUTE PROCEDURE $procedure (";
		if (is_array($parametros)) $params = $parametros;
		else $params = explode("|",$parametros);
		for ($i=0; $i < count($params); $i++) {
			if (substr($params[$i],0,7) == "(SELECT") {
				$sql .= trim($params[$i]) . ",";
			} elseif ($params[$i] == "NULL") {
				$sql .= "NULL,";
			} elseif ($params[$i] == NULL) {
				$sql .= "NULL,";
			} else {
				//$sql .= "'".$this->util->trocaacento(trim($params[$i]))."',";
				$sql .= "?,";
				$args[] = $params[$i];
				//$sql .= "'".trim($params[$i])."',";
			}
		}
		$sql = substr($sql,0,-1) . ")";
		//echo $sql;
		$arg = NULL;
		//var_dump($args);
		if (is_array($args)) {
			for ($u=0;$u < count($args);$u++) {
				$arg .= '$args['.$u.'], ';
			}
			$arg = substr($arg,0,-2);
			//eval("\$data = ibase_query(\$this->conn, \$sql, $arg);");
		} else $data = mysqli_query($sql, $this->conn);
		return $data;
		unset($data);
	}

	/**
	 * Função para seleção de Procedures.
	 *
	 * Execute as procedures com os parametros passados.
	 * @access public
	 * @param string $procedure Nome da procedure a ser executada.
	 * @param string $parametros Os parametros para executar a procedure.
	 * Podem ser dividos em um array, ou passados como string divididos por " | "
	 * @return Query
	 */
	function queryProcedure($procedure,$parametros) {
		$sql = "SELECT * FROM $procedure (";
		if (is_array($parametros)) $params = $parametros;
		else $params = explode("|",$parametros);
		for ($i=0; $i < count($params); $i++) {
			//$sql .= ($params[$i] == "NULL") ? "NULL," : "'".$this->util->trocaacento(trim($params[$i]))."',";
			$sql .= ($params[$i] == "NULL") ? "NULL," : "'".trim($params[$i])."',";
		}
		$sql = substr($sql,0,-1) . ")";
		//tirar echo $sql;
		$data = mysqli_query($sql, $this->conn);
		return new MyQuery($data,0,0);
		unset($data);
	}

	/**
	 * Função que retorna uma tabela (grid), com base numa consulta SQL e estrurada cfme uma especificação.
	 *
	 * @access public
	 * @param string $sql String com instrução SQL, ou "GRID".
	 * @param array $tabela Matriz com cabeçalhos (tabela[0]) e estrutura (tabela[1]).
	 * Opcionais:
	 * $estrutura = (
	 * 	[0] => array("cabecalho1", "cabecalho2", "cabecalho3"...),
	 * 	[1] => array("%[campo1]%",  "%[campo2]% xxxx %[campo3]%", "xxxxx %[campo4]% xxxxxxx"...),
	 * 	['key'] => array("campo1", "campo4"...),
	 * 	[2] => array(
	 * 		‘$temp = funções PHP, executadas pelo eval do cabecalho1’,
	 * 		‘executadas pelo eval do cabecalho2’..)
	 * ).
	 * @param string $idTabela Identificador da função.
	 * @param string $flCtrl Contém o que a coluna ctrl deve mostrar: “N” Nenhuma, “S” Seleção, “A” Alteração ou “AS”.
	 * O atributo tabela['key'] é obrigatório. $flCtrl = {default: "N" || "S" || "XXXX"}
	 * @param array $opInfo Quando informado, a feita verificação dos campos informados e mostrado
	 * o conteúdo no TPL especificado, através de ativação de sua função JS: informacao(‘chave’).
	 * $opInfo = {default: NULL || "falta implementação"}
	 * @param int $pag Contém o número de registros por página. DEFAULT 25.
	 */
	function getTabela($sql, $estrutura, $idTabela, $flCtrl = "N", $opInfo = null, $pag = 25, &$objRemoto=null) {
		$UI = new UI($this->smarty);
		if ($sql=="GRID") {
			$sql = $_SESSION[$idTabela]['sqlTabela'];
			$estrutura = $_SESSION[$idTabela]['tabela'];
			$opInfo = $_SESSION[$idTabela]['opInfo'];
			$_SESSION[$idTabela]['novo'] = 0;
			$grid["paginacao"] = $_SESSION[$idTabela]['paginacao'];
			$grid["paginacao"]["pagAtual"] = $pag-1;
		} else {
			$grid["paginacao"]["regPagina"] = $pag;
			$grid["paginacao"]["pagAtual"] = 0;
			$_SESSION[$idTabela]['novo'] = 1;
			$_SESSION[$idTabela]['flCtrl'] = $flCtrl;
		}

		$paginacao = array(
			"registros" => $grid["paginacao"]["regPagina"],
			"pagina" => $grid["paginacao"]["pagAtual"]);
		$data = $this->selectQuery($sql,$paginacao);
		$gridDados = $data->getGrid();
		$gridDados["paginacao"] = $grid["paginacao"];
		$paginacao = $data->getNext();

		if ($paginacao["registros"]!=0 && $paginacao["pagina"]==1) {
			$data2 = $this->selectQuery($sql);
			$gridDados["paginacao"]["totReg"] = $data2->getNumLinhas();
			unset($data2);
		}
		$gridDados["paginacao"]["pagAtual"] = $paginacao["pagina"];
		$grid = $gridDados;
		unset($gridDados);
		$_SESSION[$idTabela]['sqlTabela'] = $sql;
		$this->smarty->assign("paginas",null);
		$this->smarty->assign("nr_paginas",null);
		$registroAdd = (isset($_SESSION[$idTabela]["registro_add"])) ? $_SESSION[$idTabela]["registro_add"] : null;
		return $UI->montarTabela($grid, $estrutura, $idTabela, $opInfo, $registroAdd, $objRemoto);
	}

	/**
	 * Função que confere os direitos de acesso do usuário atual a tabela.
	 *
	 * Assimila às variáveis do smarty, os direitos de acesso.
	 * @author Anderson Jordão Marques <ajm at urbanauta com br>
	 * @version 1.0
	 * @param String $idTabela Identificador da tabela no banco.
	 * @param String $flCtrl Opção de controle.
	 * @return void
	 */
	function conferirDireitoAcesso($idTabela) {
		$flCtrl = $_SESSION[$idTabela]['flCtrl'];
		//echo $idTabela." ";
		$alt = $inc = $inf = $exc = "N";
		if ($flCtrl != "S" && $flCtrl != "" && $flCtrl != null) {
			$sel = "N";
			$dsUsu = $_SESSION['dsUsuario'];
			if ($dsUsu == "SUPERVISOR") $alt = $inc = "S";
			else {
				$cdUsu = $_SESSION['cdUsuario'];
				$permissao = $this->selectQuery("SELECT * FROM TAB_MENU_USUARIO " .
						"WHERE CD_USU='$cdUsu' AND CD_MENU='$idTabela'");
				$alt = $permissao->getReg("FL_ALTERAR");
				$inc = $permissao->getReg("FL_INCLUIR");
				unset($permissao);
			}

			if (isset($_SESSION[$idTabela]['alt'])) $alt = $_SESSION[$idTabela]['alt'];
			else $alt = (substr_count($flCtrl,"A") > 0) ? $alt : "N";

			if (isset($_SESSION[$idTabela]['exc'])) $exc = $_SESSION[$idTabela]['exc'];
			else $exc = (substr_count($flCtrl,"E") > 0) ? "S" : "N";

		} else {
			$sel = substr_count($flCtrl,"S") > 0 ? "S" : "N";
			//$c = 0;
		}

		$this->smarty->assign("alt",$alt);
		$_SESSION[$idTabela]['alt'] = $alt;

		$this->smarty->assign("inc",$inc);
		$_SESSION[$idTabela]['inc'] = $inc;

		$this->smarty->assign("inf",$inf);
		$_SESSION[$idTabela]['inf'] = $inf;

		$this->smarty->assign("exc",$exc);
		$_SESSION[$idTabela]['exc'] = $exc;

		$this->smarty->assign("sel",$sel);
		$_SESSION[$idTabela]['sel'] = $sel;
	}

	/**
	 * Função para inserir/atualizar campos BLOB
	 *
	 * @return blob um valor para campo blob para interbase/firefox.
	 * @param string $str String para ser convertida.
	 */
	public function strToMemo($str) {
		$id = ibase_blob_create();
		ibase_blob_add( $id, $str );
		return ibase_blob_close( $id );
	}

	/**
	 * Função para converter data no formato dd/mm/aaaa para TIMESTAMP do mysql.
	 *
	 * @return string Data no formato aaaa-mm-dd.
	 * @param string $data Data no formato dd/mm/aaaa.
	 */
	public function strToDate($data) {
		if ($data != "") {
			$dt = explode("/",$data);
			return $dt[2]."-".$dt[1]."-".$dt[0];
			//return str_replace("/",".",$data);
		} else return "";
	}

} // fim da classe DataBase
?>
