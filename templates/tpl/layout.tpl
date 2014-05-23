{config_load file="urbanauta.conf"  section='smarty_vars'}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">
<head>
	<!-- META -->
	<TITLE>{#titulo#}</TITLE>

	<META NAME="title" CONTENT="{#titulo#}" />
	<META NAME="description" CONTENT="{#descricao#}" />
	<META NAME="keywords" CONTENT="{#palavra_chave#}" />
	<META NAME="generator" CONTENT="{#generator#}" />
	<META NAME="author" CONTENT="{#autor#}" />

	<meta name="resource-type" content="document" />
	<meta name="revisit-after" content="1" />
	<meta name="classification" content="{#classificacao#}" />
	<meta name="distribution" content="Global" />
	<meta name="language" content="pt-br" />
	<meta name="verify-v1" content="1qD1cUOSn/7cDQtz48tbdYM9mjbuItgoA0PEw5OxjCM=" />

{if $indexa==true}
	<meta name="ROBOTS" content="ALL,INDEX,FOLLOW">
{else}
	<meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
{/if}

	<!-- ICBM -->
	<meta name="ICBM" content="-26.2884,-48.8328" />
	<meta name="DC.title" content="{#titulo#}" />

	<!-- RSS
	<link type="application/atom+xml" href="atom.php" rel="alternate" title="Atom" />
	<link type="application/rss+xml" href="rss.php" rel="alternate" title="RSS 2.0" />
	-->

	<!-- CSS -->
	{section name=linha loop=$css}
	<link type="{$css[linha].type}" href="{$css[linha].src}" media="{$css[linha].media}" rel="{$css[linha].rel}" title="{$css[linha].tit}" />
	{/section}

	<link type="image/x-icon" href="img/favicon.ico" rel="icon" />
	<link type="image/x-icon" href="img/favicon.ico" rel="shortcut icon" />

	<!-- JS -->
	{section name=linha loop=$js_scripts}
	<script type="text/javascript" src="{$js_scripts[linha]}"></script>
	{/section}
</head>

<body>

<!-- Layout original 800 x 600 -->
	<div id="header">
		<img id="grafx" src="img/urbanauta.png">
		<img id="name" src="img/urbanauta_ds.png">
	</div>

	
	<nav>
		<ul class="nav nav-pills nav-stacked">
			<li class="active transiction-teste"><a href="#grupoA">Grupo A</a></li>
			<li><a href="#grupoB">Grupo B</a></li>
			<li><a href="#grupoC">Grupo C</a></li>
			<li><a href="#grupoD">Grupo D</a></li>
			<li><a href="#grupoE">Grupo E</a></li>
			<li><a href="#grupoF">Grupo F</a></li>
			<li><a href="#grupoG">Grupo G</a></li>
			<li><a href="#grupoH">Grupo H</a></li>    
		</ul>
	</nav>


	<div id="leftCol">
	<div id="rightCol">{$ds_conteudo}</div>
</body>
</html>