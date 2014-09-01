<?php
require_once("config.php");
//require_once("core/class.Auth.php");
//require_once("core/class.Crypto.php");
require_once("core/class.MyDataBase.php");
require_once("core/class.TimeDate.php");
//require_once("core/class.Menu.php");
//require_once("core/class.Usuario.php");
require_once("core/class.Util.php");
require_once("core/class.UI.php");

/**
 * Classe que cont�m a configura��o padr�o da aplica��o WEB.
 *
 * Esta classe inicia e instancia os objetos.
 * Com base nessa classe, todas as outras fun��es do site trabalham.
 * Aqui s�o instanciados os objetos "DEFAULT" para a aplica��o.
 *
 * @access public
 * @author Leonardo Cidral <lcidral@gmail.com>
 * @author Anderson Jord�o Marques <ajm@urbanauta.com.br>
 * @copyright Copyright &copy; 2006-2008, Portal Urbanauta LTDA.
 * @package CMW KAVO 2.0
 * @since 1.1 - 31/07/2006
 * @since 1.0 - 24/07/2006
 */
class Setup {

	private $script_start = null;
	private $script_end = null;
	private $elapsed_time = null;

	/**
	 * @var smarty_setup
	 */
	public $smarty;

	/**
	 * @var MyDataBase
	 */
	public $conn;
	public $crypto;
	/**
	 * @var Auth
	 */
	public $auth;
	public $ojMenu;
	public $util;
	/**
	 * @var UI
	 */
	public $UI;
	public $timeDate;

	private $jssrc = array();
	private $css = array();

	/**
	 * M�todo construtor da Classe.
	 *
	 * Inicia a contagem para medir a performance, e
	 * declara jssrc. Declara e atribu� a vari�vel PATH
	 * com o caminho relativo da aplica��o.
	 * @todo Aqui devem ser instanciados os objetos DEFAULT. ex: DataBase, DateTime.
	 * @version 1.0
	 * @since 1.0 - 24/07/2006
	 */
	public function Setup($str_arquivo="conf/urbanauta.conf") {

		if (!$this->loadConfig($str_arquivo)) die("Erro ao carregar o arquivo:<strong> ".$str_arquivo."</strong><br>".$_SERVER["PHP_SELF"]);

		set_include_path(INCLUDE_PATH);
		require_once("libs/Smarty.class.php");
		require_once("smarty_setup.php");

		session_start();

		//AUXILIAR
		$this->performance_start();

		//CORE
		$this->smarty = new smarty_setup();

		$this->conn = new MyDataBase(MY_HOST, MY_DATA, MY_USER, MY_PASS, $this->smarty);
		$this->timeDate = new TimeDate();
		$this->UI = new UI($this->smarty);
		$this->util = new Util($this->conn,$this->smarty);
		/* TODO Corrigir esse Carregamento
		 $this->crypto = new Crypto();
		 $this->auth = new Auth(&$this->conn, &$this->crypto);
		 $this->ojMenu = new Menu(&$this->conn, &$this->smarty);
		 $this->usuario = new Usuario(&$this->conn, &$this->smarty, &$this->crypto, &$this->UI);*/
	}


	function loadConfig($str_arquivo) {
		$retorno = false;
		if (file_exists($str_arquivo)) {
			$config = parse_ini_file($str_arquivo,true);
			foreach($config as $nomeGrupo => $arrGrupo) {
				foreach($arrGrupo as $constante => $valor) define($constante,$valor);
			}
			$retorno = true;
		}
		return $retorno;
	}


	/**
	 * Esta fun��o recebe os elementos, e mostra uma p�gina com layout predefinido em layout.tpl
	 *
	 * fun��o pagina recebe como parametro obrigat�rio o conte�do, e como
	 * par�metros adicionais mais estilos CSS especificos, JS adicionais e
	 * um valor booleano indicando a indexa��o por rob�s de busca
	 * (false para n�o indexar, valor padr�o)
	 *
	 * @version 1.1
	 * @since 1.0 - 24/07/2006
	 * @param resource tpl/html $ds_conteudo Conte�do da p�gina. Obrigat�rio.
	 * @param bool $indexa indica a indexa��o por rob�s de busca, false (n�o indexar)/true(indexa). DEFAULT false.
	 */
	function pagina($indexa=false,$conteudo) {
		if (!isset($_POST["arquivo"])) {
			//Informa se � para os rob�s de busca estas paginas ou n�o
			$this->smarty->assign("indexa",$indexa);

			//data de hoje
			$dt_hoje = $this->timeDate->hoje();
			$this->smarty->assign("dt_hoje",$dt_hoje);
			$this->smarty->assign("path_tmp","");

			$this->smarty->assign("js_scripts",$this->getJS());
			$this->smarty->assign("css",$this->getCSS());

			$this->smarty->assign("favicon","favicon.ico");

			//$menu = $this->ojMenu->geraMenu($this->auth->chk_sessao());
			//$this->smarty->assign("menu",$menu);

			$this->smarty->assign("url_app", URL_BASE);

			$this->smarty->assign("sg_language", LANGUAGE);
			//$this->smarty->assign("unid_monetaria", UNID_MONETARIA);
			$this->smarty->assign("formato_data", FORMATO_DATA);
			$this->smarty->assign("formato_hora", FORMATO_HORA);

			//$this->smarty->assign("mes_nome", MES_NOME);
			//$this->smarty->assign("mes_abrv", MES_ABRV);

			//$this->smarty->assign("dias", DIAS);
			//$this->smarty->assign("dias_abrv", DIAS_ABRV);
			$this->smarty->assign("sg_language", LANGUAGE);


			$this->performance_total();

			//invoca o template
			$this->smarty->assign("ds_conteudo",$conteudo);
			$this->smarty->display('layout.tpl');
			$this->smarty->clearCompiledTemplate();
		} else {
			echo $conteudo;
		}
	}

	function getRelativeAdress(){
		$_ADRESS_OFF_THIS_SCRIPT_ = $_SERVER['SCRIPT_NAME'];
		$_RELATIVE_LEVEL_FOLDER_ = (substr_count($_ADRESS_OFF_THIS_SCRIPT_, "/") - 2);
		$str = str_repeat("../", $_RELATIVE_LEVEL_FOLDER_);
		return $str;
	}

	//includes javascript
	function addJS($js_src) {
		$this->jssrc[] = $js_src;
	}
	function getJS() { return $this->jssrc;	}

	//includes CSS
	function addCSS($css_src,$css_type,$css_media,$css_rel,$css_tit) {
		$this->css[] = array(
			"src" => $css_src,
			"type" => $css_type,
			"media" => $css_media,
			"rel" => $css_rel,
			"tit" =>$css_tit);
	}

	function getCSS() { return $this->css; }

	//monta string para id do menu
	function monta_id($str) {
		$temp = trim($str);
		$temp = strtolower($temp);
		$temp =	str_replace(" ","",$temp);
		$temp = $this->util->trocaacento($temp);
		return "id_".$temp;
	}


	//MEDE O TEMPO DE CARREGAMENTO DA P�GINA
	function performance_start() {
		list($usec, $sec) = explode(" ", microtime());
		$this->script_start = (float) $sec + (float) $usec;
	}

	function performance_end() {
		list($usec, $sec) = explode(" ", microtime());
		$this->script_end = (float) $sec + (float) $usec;
		$this->elapsed_time = round($this->script_end - $this->script_start, 2);
	}

	function performance_total() {
		$this->performance_end();
		$this->smarty->assign("performance"," [".$this->elapsed_time."s]");
	}
}
?>