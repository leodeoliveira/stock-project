{config_load file="urbanauta.conf" section="smarty_vars"}
{if $isNew}
<div id="fullgrid">
	{if $pags && $numPags != 1}
	<div id="barra_navegacao_{$idTabela}" class="barra_navegacao">

		<div id="ctrl_esq_{$idTabela}" style="float:left;">
			<span onclick="xpaginacao.primeira('{$idTabela}','{$idJS}');" class="pg_primeira" id="pg_primeira_{$idTabela}">&nbsp;</span>
			<span onclick="xpaginacao.anterior('{$idTabela}','{$idJS}');" class="pg_anterior" id="pg_anterior_{$idTabela}">&nbsp;</span>
		</div>

		<div id="nrPaginasTop_{$idTabela}" class="ConteinerNrpags" style="width: {if ($numPags > 10)}340{else}$numPags * 34{/if}px;">
			{section name=linha loop=$pags}
				<span onclick="xpaginacao.pagina({$pags[linha]},'{$idTabela}','{$idJS}');" onmouseover="xpaginacao.sinaliza_pagina(this);" onmouseout="xpaginacao.sinaliza_pagina(this);" class="{if $pags[linha] == 1}nr_pagina_sel{else}nr_pagina{/if}" id="pg_{$pags[linha]}_top_{$idTabela}" style="float: left;">{$pags[linha]}</span>
			{/section}
		</div>

		<div id="ctrl_dir_{$idTabela}" style="float:left;">
			{if ($numPags > 10)}
			<button id="ctlr_nrpags" class="ctrl_nrpags" onMouseUp="xpaginacao.cortinapags('nrPaginasTop_{$idTabela}',true);">+</button>
			{/if}

			<span onclick="xpaginacao.proxima('{$idTabela}','{$idJS}');" class="pg_proxima" id="pg_proxima_{$idTabela}">&nbsp;</span>
			<span onclick="xpaginacao.ultima('{$idTabela}','{$idJS}');" class="pg_ultima" id="pg_ultima_{$idTabela}">&nbsp;</span>
			&nbsp;&nbsp;
			Ir para:&nbsp;<input name="nr_pagina_atual_old_{$idTabela}" id="nr_pagina_atual_old_{$idTabela}" type="hidden" value="1" readonly /><input id="nr_pagina_atual_{$idTabela}" type="text" maxlength="4" class="pg_carregada" onBlur="xpaginacao.vaipara(this.value,'{$idTabela}','{$idJS}');" value="1" onKeyPress="return app.apenas_inteiros(event, this);">&nbsp;/&nbsp;<span id="nr_pagina_total_{$idTabela}">{$numPags}</span>
		</div>
	</div>
	{/if}

	{if $numPags > 1}<div style="height: 25px;">&nbsp;&nbsp;</div>{/if}
	<div id="gridTabela_{$idTabela}" class="gridtabela">
{/if}
<table class="grid" id="{$idTabela}">
	{if $titTabela}
	<caption class="caption">{$titTabela}</caption>
	{/if}
	{if $isCabecalho}
	<thead>
		{$extraFirstLine}
		<tr>
			{section name=j loop=$colunas}
			{if not $ordJS}
			<th id="th_{$idTabela}_{$colunas[j].id}"
			{if $colunas[j].ordenar}onclick="xgrid.ts_resortTable(this, '{$smarty.section.j.index}','{$idTabela}','{$idJS}','{$colunas[j].ordenar}');return false;"{/if}>&nbsp;&nbsp;&nbsp;{$colunas[j].cabecalho}<span id="spanord_{$idTabela}_{$smarty.section.j.index}" class="sortarrow">&nbsp;&nbsp;&nbsp;</span></th>
			{else}
			<th id="th_{$idTabela}_{$colunas[j].id}"
			{if $colunas[j].ordenar}onclick="ts_resortTable(this, '{$smarty.section.j.index}');return false;"{/if}>&nbsp;&nbsp;&nbsp;{$colunas[j].cabecalho}<span class="sortarrow" sortdir="down">&nbsp;&nbsp;&nbsp;</span></th>
			{/if}
			{/section}
		</tr>
	</thead>
	{/if}
	{if $isRodape}
	<tfoot>
		<tr>
			{section name=j loop=$colunas}
			<td id="tf_{$idTabela}_{$colunas[j].id}" class="tipo_{$colunas[$smarty.section.j.index].tipo}">{$colunas[j].rodape}</td>
			{/section}
		</tr>
	</tfoot>
	{/if}
	<tbody>
		{foreach name=l item=lin from=$linhas}
		<tr class="{cycle values="cor1,cor2"}">
			{foreach name=c item=col from=$lin}
			<td class="td_{$idTabela}_{$colunas[$smarty.foreach.c.index].id} tipo_{$colunas[$smarty.foreach.c.index].tipo}">{$col}</td>
			{/foreach}
		</tr>
		{/foreach}
	</tbody>
</table>
{if $isNew}
	</div>
</div>
{/if}

{if $isNew && $numPags == 1}
	<div style="height: 0px;">
	<input name="nr_pagina_atual_{$idTabela}" id="nr_pagina_atual_{$idTabela}" type="hidden" value="1" />
	<input name="nr_pagina_atual_old_{$idTabela}" id="nr_pagina_atual_old_{$idTabela}" type="hidden" value="1" />
	</div>
{/if}