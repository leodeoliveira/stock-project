<table class="grid" id="{$idTabela}">
	{if $titTabela}
	<caption>{$titTabela}</caption>
	{/if}
	{if $isCabecalho}
	<thead>
		<tr>
			{section name=j loop=$colunas}
			<th>{$colunas[j].cabecalho}</th>
			{/section}
		</tr>
	</thead>
	{/if}
	{if $isRodape}
	<tfoot>
		<tr>
			{section name=j loop=$colunas}
			<td>{$colunas[j].rodape}</td>
			{/section}
		</tr>
	</tfoot>
	{/if}
	<tbody>
		{foreach name=l item=lin from=$linhas}
		<tr class="{cycle values=impar,par}">
			{foreach name=c item=col from=$lin}
			<td>{$col}</td>
			{/foreach}
		</tr>
		{/foreach}
	</tbody>
</table>