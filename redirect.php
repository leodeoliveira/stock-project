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
		include("lib/scripts/" . $_REQUEST["arquivo"] . ".js");
		break;
	case "jsh":
		header("Content-type: text/javascript");
		include("controller/js/" . $_REQUEST["arquivo"] . ".js");
		break;
	case "json":
		header("Content-type: application/json");
		$age = 86400;
		if (stripos($_REQUEST["pastas"], "/prefetch") !== FALSE) {
			header("Pragma: no-cache");
			header("Cache-Control: no-cache");
			header("Expires: " . str_replace("+0000", "GMT", gmdate("r", time() - $age * 360)));
		} else {
			header_remove("Pragma");
			header("Cache-Control: public, must-revalidate, max-age=". $age . ", s-maxage=". $age);
			header("Expires: " . str_replace("+0000", "GMT", gmdate("r", time() + $age)));
		}
	case "php":
		if (stripos($_REQUEST["pastas"], "/core") !== FALSE) {
			include("core/grid.php");
			break;
		}
	default:
		include("controller/default.php");
		break;
}
?>
