<div id="livrinho_{$objID}" class="livrinho">
<div class="tt_livrinho">
	<div class="conteiner_livrinho">
		<span class="texto_titulo">
			<img src="resources/img/{if $ig_livrinho}{$ig_livrinho}{else}livro.gif{/if}" id="bt_ico_fechar_livrinho" onmouseup="app.ocultaBackDiv();$('livrinho_{$objID}').remove();" class="icone_titulo" />
			&nbsp;{$tt_livrinho}
		</span>
		<p><img src="resources/img/transparente.gif" id="bt_img_fechar_livrinho" class="bt_fechar_livrinho" onmouseup="app.ocultaBackDiv();$('livrinho_{$objID}').remove();" /></p>
	</div>

	<div id="conteudo_livrinho_{$objID}" class="conteudo_livrinho">
		{$ct_livrinho}
	</div>
</div>
</div>