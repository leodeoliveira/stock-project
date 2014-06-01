<fieldset>
<legend>Busca</legend>
<form action="#" method="get" onSubmit="filtrar('{$objJS}'); return false;" id="frmBusca">
<table width="200" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td><label id="lb_campo_{$objJS}" for="ds_campo_{$objJS}">Campo:</label></td>

    <td>
		<select name="ds_campo_{$objJS}" id="ds_campo_{$objJS}">
		{html_options options=$op_campos}
		</select>
	</td>

    <td><label id="lb_criterio_{$objJS}" for="ds_criterio_{$objJS}" style="float: left;">Critério:</label></td>

    <td><input name="ds_criterio_{$objJS}" id="ds_criterio_{$objJS}" type="text" size="20" maxlength="40" /></td>

    <td><input name="bt_filtrar_{$objJS}" type="submit" value="Buscar" id="bt_filtrar_{$objJS}" /></td>
  </tr>
</table>
</form>
</fieldset>

<br />