{config_load file="urbanauta.conf" section="smarty_vars"}
{if $primeiro}
<div id="fullgrid">
{/if}

	{if $paginas}
	<div id="barra_navegacao_{$cdTela}">

		<div id="ctrl_esq_{$cdTela}" style="float:left;">
			<span onmouseup="xpaginacao.primeira('{$cdTela}');" class="pg_primeira" id="pg_primeira_{$cdTela}"></span>
			<span onmouseup="xpaginacao.anterior('{$cdTela}');" class="pg_anterior" id="pg_anterior_{$cdTela}"></span>
		</div>

		<div id="nrPaginasTop_{$cdTela}" name="nrPaginasTop_{$cdTela}" class="ConteinerNrPaginas" style="width: {if ($nr_paginas > 10)}340{else}$nr_paginas * 34{/if}px;">
			{section name=linha loop=$paginas}
				<span onmouseup="xpaginacao.pagina({$paginas[linha]},'{$cdTela}');" onmouseover="xpaginacao.sinaliza_pagina(this);" onmouseout="xpaginacao.sinaliza_pagina(this);" class="{if $paginas[linha] == 1}nr_pagina_sel{else}nr_pagina{/if}" id="pg_{$paginas[linha]}_top_{$cdTela}" style="float: left;">{$paginas[linha]}</span>
			{/section}
		</div>

		<div id="ctrl_dir_{$cdTela}" style="float:left;">
			{if ($nr_paginas > 10)}
			<button id="ctlr_nrpaginas" class="ctrl_nrPaginas" onMouseUp="xpaginacao.cortinaPaginas('nrPaginasTop_{$cdTela}',true);">+</button>
			{/if}

			<span onmouseup="xpaginacao.proxima('{$cdTela}');" class="pg_proxima" id="pg_proxima_{$cdTela}"></span>
			<span onmouseup="xpaginacao.ultima('{$cdTela}');" class="pg_ultima" id="pg_ultima_{$cdTela}"></span>
			&nbsp;&nbsp;
			Ir para:&nbsp;<input name="nr_pagina_atual_old_{$cdTela}" id="nr_pagina_atual_old_{$cdTela}" type="hidden" value="1" readonly /><input id="nr_pagina_atual_{$cdTela}" type="text" maxlength="4" class="pg_carregada" onBlur="xpaginacao.vaipara(this.value,'{$cdTela}');" value="1" onKeyPress="return app.apenas_inteiros(event, this);">&nbsp;/&nbsp;<span id="nr_pagina_total_{$cdTela}">{$nr_paginas}</span>
		</div>
	</div>
	{/if}


	{if $nr_paginas}<div style="height: 25px;">&nbsp;&nbsp;</div>{/if}
	{if $primeiro}
	<div id="gridtabela_{$cdTela}" class="gridtabela">
	{/if}
		<table border="0" align="center" cellpadding="1" cellspacing="1" class="sortable grid" id="{$cdTela}_grid" width="100%">
			<thead>
			<tr>
			{section name=col loop=$cabecalho.ds}
				{if $ordenacao > 1}
				<th id="{$cdTela}_{$smarty.section.col.index}" onclick="xgrid.ts_resortTable(this, '{$smarty.section.col.index}','{$cdTela}','{$cabecalho.ord[col]}');return false;" class="th_titulo">&nbsp;&nbsp;&nbsp;{$cabecalho.ds[col]}<span id="spanord_{$cdTela}_{$smarty.section.col.index}" class="sortarrow">&nbsp;&nbsp;&nbsp;</span></th>
				{else}
				<th id="{$cdTela}_{$smarty.section.col.index}" onclick="ts_resortTable(this, '{$smarty.section.col.index}','{$cdTela}');return false;" class="th_titulo">&nbsp;&nbsp;&nbsp;{$cabecalho.ds[col]}<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></th>
				{/if}
			{/section}
			</tr>
			</thead>
			<tbody>
			{section name=linha loop=$registro}
			<tr class="{cycle values="cor1,cor2"}" onmouseover="this.style.border='1px solid #316AC5'; this.style.backgroundColor='#FADCB4';" onmouseout="this.style.border=''; this.style.backgroundColor='';">
				{section name=coluna loop=$cabecalho.cd}
					<td id="{$cdTela}_{$smarty.section.coluna.index}_{$smarty.section.linha.index}" class="{$cdTela}_col_{$cabecalho.cd[coluna]} tipo_{$cabecalho.tipo[coluna]}">{$registro[linha][coluna]}</td>
				{/section}
			</tr>
			{/section}
			</tbody>
			<tfoot>
			{section name=linha loop=$rodape}
				<tr id="tr_edit_{$smarty.section.linha.index}" style="font-weight:bolder; background-image: url(skin/{#NM_TEMA#}/img/degrade-fundo-grid.gif); background-repeat: repeat-x;">
				{section name=coluna loop=$cabecalho.cd}
					<td class="{$cdTela}_col_{$cabecalho.cd[coluna]}">{$rodape[linha][coluna]}</td>
				{/section}
				</tr>
			{/section}
			{section name=linha loop=$registro_add}
				<tr id="tr_edit" style="background-image: url(skin/{#NM_TEMA#}/img/degrade-fundo-grid.gif); background-repeat: repeat-x;" {if $class_add}class="class_add" {/if}{if $mouse_over}onMouseOver="$mouse_over" {/if}{if $mouse_out}onMouseOut="$mouse_out"{/if}>
				{section name=coluna loop=$cabecalho.cd}
					<td class="col_{$cabecalho.cd[coluna]}">{$registro_add[linha][coluna]}</td>
				{/section}
				</tr>
			{/section}
			</tfoot>
		</table>

{if $primeiro}
	</div>
</div>
{/if}

{if not $paginas && $primeiro}
	<div style="height: 0px;">
	<input name="nr_pagina_atual_{$cdTela}" id="nr_pagina_atual_{$cdTela}" type="hidden" value="1" />
	<input name="nr_pagina_atual_old_{$cdTela}" id="nr_pagina_atual_old_{$cdTela}" type="hidden" value="1" />
	</div>
{/if}