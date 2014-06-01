<div id="frm{$cd_tela}" class="janela">
	<div id="barra_titulo_janela_frm{$cd_tela}" style="height: 26px;">
		<button class="ico_janela" id="ico_janela_frm{$cd_tela}" onclick="fechar_janelinha('frm{$cd_tela}');{$fecharExtraJS}" title="{$FECHAR}"><img src="{$root_path}resources/img/{if $ig_janela}{$ig_janela}{else}ico_janela.gif{/if}"/></button>
		<h1 class="tt_janela">{$tt_tela}</h1>
		<button id="fechar_frm{$cd_tela}" class="bt_fechar" onclick="fechar_janelinha('frm{$cd_tela}');{$fecharExtraJS}" title="{$FECHAR}"><img src="{$root_path}resources/img/bt_fechar_janela.gif"/></button>
	</div>
	<div id="conteudo_janela_frm{$cd_tela}" class="conteudo_janela">
	{$ds_tela}
	</div>
</div>