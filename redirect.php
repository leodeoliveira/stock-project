<?php
require_once("conf/class.Setup.php");
$setup = new Setup();

switch ($_REQUEST["ext"]) {
	case "css":
		header("Content-type: text/css");
		include("skin/".NM_TEMA."/" . $_REQUEST["arquivo"] . ".css");
		break;
	case "png":
	case "jpg":
	case "gif":
	case "ico":
		header("Content-type: image/". $_REQUEST["ext"]);
		if (substr_count($_REQUEST["arquivo"], "urbanauta") > 0) include("img/" . $_REQUEST["arquivo"] . ".".$_REQUEST["ext"]);
		else if (substr_count($_REQUEST["arquivo"], "favicon") > 0) include("img/" . $_REQUEST["arquivo"] . ".".$_REQUEST["ext"]);
		else include("skin/".NM_TEMA."/img/" . $_REQUEST["arquivo"] . ".".$_REQUEST["ext"]);
		break;
	case "js":
		header("Content-type: text/javascript");
		include("lib/scripts/" . $_REQUEST["arquivo"] . ".".$_REQUEST["ext"] );
		break;
	default:
		include("controller/default.php");
		break;
}
?>