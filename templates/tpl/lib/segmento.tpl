{config_load file="commerce.conf" section="smarty_vars"}
<div class="barra_segmento" onmouseup="alterna('cont_{$cd_segmento}');">
	<span class="img_toggle_{if $ocultar}off{else}on{/if}" id="cont_{$cd_segmento}_img_toggle"></span>
	<span class="tt_segmento" id="tt_segmento_{$cd_segmento}">{$tt_segmento}</span>
</div>
<div id="cont_{$cd_segmento}" class="conteudo_segmento" style="{if $ocultar}display: 'none';{/if}{$styleExtra}">
{$conteudo_segmento}
</div>