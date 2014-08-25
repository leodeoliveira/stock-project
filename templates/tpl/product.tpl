<div class="col-md-offset-2">
	<div id="form-trans">
		<form role="form" class="form-horizontal" action="product.put"
			method="post">
			<div class="form-group">
				<label for="id_product" class="col-sm-2 control-label">C&oacute;digo
					*</label>
				<div class="col-sm-5">
					<input type="text" id="id_product" name="id_product"
						class="form-control" value="{$id_product}" disabled="disabled" />

					<input type="hidden" id="id" name="id" value="{$id_product}" />
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-sm-2 control-label">Descri&ccedil;&atilde;o
					*</label>
				<div class="col-sm-5">
					<input type="text" id="description" name="description"
						class="form-control" value="{$description}"
						placeholder="Digite a descri&ccedil;&atilde;o..."
						required="required" />
				</div>
			</div>
			<div class="form-group">
				<label for="unit_value" class="col-sm-2 control-label">Valor
					Unit&aacute;rio *</label>
				<div class="col-sm-5">
					<input type="number" id="unit_value" name="unit_value"
						class="form-control" value="{$unit_value}"
						placeholder="Digite o valor unit&aacute;rio..." min="0.01"
						step="0.01" value="1.00" required="required" />
				</div>
			</div>
			<div class="form-group">
				<label for="note" class="col-sm-2 control-label">Observa&ccedil;&otilde;es
					*</label>
				<div class="col-sm-5">
					<textarea id="note" name="note" class="form-control"
						placeholder="Digite as informa&ccedil;&otilde;es adicionais...">{$note}</textarea>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 pull-right">
					<button class="btn btn-success" type="submit">Salvar</button>
				</div>
			</div>
		</form>
	</div>
	{$tabela}
</div>
