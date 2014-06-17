{config_load file="commerce.conf" section="smarty_vars"}
<div id="livrinho_{$objID}" class="livrinho">
<div class="tt_livrinho">
	<div class="conteiner_livrinho">
		<span class="texto_titulo">
			<img src="skin/{#NM_TEMA#}/ico/{if $ig_livrinho}{$ig_livrinho}{else}livro.gif{/if}" id="bt_ico_fechar_livrinho" onmouseup="app.ocultaBackDiv();Element.remove('livrinho_{$objID}');" class="icone_titulo" />
			&nbsp;{$tt_livrinho}
		</span>
		<p src="skin/{#NM_TEMA#}/ico/transparente.gif" id="bt_img_fechar_livrinho" class="bt_fechar_livrinho" onmouseup="app.ocultaBackDiv();Element.remove('livrinho_{$objID}');" /></p>
	</div>
{if $bxcamp == "1"}
<br/>
&nbsp;&nbsp;<b>Nova data de remessa:</b>&nbsp;
<input type="text" value="" name="Nova_DT_REMESSA_IA" id="Nova_DT_REMESSA_IA" maxlength="10" class="campoedit" size="9" onKeyPress="return Mascarar_Data(window.event.keyCode,this); app.apenas_inteiros(event);"/>
<br/><br/>
{/if}
	<div id="conteudo_livrinho_{$objID}" class="conteudo_livrinho">
		{$ct_livrinho}
	</div>
{if $bxcamp == "1"}
<br/>
<div align="center">
<input name="bt_fechar" type="submit" id="bt_fechar" value="&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;" onclick="pedv_cjtos.fechajanela_DataRemessa();">
<br/><br/>
</div>
{/if}
</div>
</div>
