{config_load file="urbanauta.conf"  section='smarty_vars'}
<!DOCTYPE HTML>
<html>
<head>
	<!-- META -->
	<title>{#titulo#}</title>

	<meta name="title" content="{#titulo#}" />
	<meta name="description" content="{#descricao#}" />
	<meta name="keywords" content="{#palavra_chave#}" />
	<meta name="generator" content="{#generator#}" />
	<meta name="author" content="{#autor#}" />

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


	<title>Controle de Estoque</title>
	<meta charset="utf8">

</head>

<body>

<!-- Layout original 800 x 600 -->
<div class="container" id="publicacoes">

	<div class="row">
		{if $include_menu == true}
		<div class="col-md-3">
			<img src="/stock-project/img/logo.jpg">
			{include file="menu.tpl"}
		</div>
		{/if}
		<div class="col-md-9" id="news" style="margin-top: 20px;">
			{$ds_conteudo}
		</div>
	</div>
</div>
{include file="footer.tpl"}

</body>
</html>
