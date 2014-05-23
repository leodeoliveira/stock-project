<?php
//variáveis de ambiente [ambiente]
if (isset($_COOKIE["sg_language"])) $language = $_COOKIE["sg_language"];
else $language = "pt_BR";
define("LANGUAGE",$language);

//variaveis do cliente [server]
define("CD_CLIENTE","1");
define("NM_SERVIDOR","http://www.urbanauta.com.br/"); //chave para quem está licenciado

//TODO Expressões regulares estão relacionada ao brasil
define("REGEX_CEP","^[0-9]{5}-{1}[0-9]{3}$");
define("REGEX_DATA","^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$");
define("REGEX_HORA","^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$");
define("REGEX_CPF","^[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}$");

define("REGEX_EMAIL","^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([a-z,A-Z]){2,3}([0-9,a-z,A-Z])?$");
define("REGEX_DECIMAL","^[+-]?((\d+|\d{1,3}(\,\d{3})+)(\.\d*)?|\.\d+)$");
?>