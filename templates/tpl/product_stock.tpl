<div class="col-md-offset-2">
	<div id="form-trans">
		<form role="form" class="form-horizontal" action="product_stock.put"
			method="post">
			<input type="hidden" name="id_product" id="id_product" />
			<input type="hidden" name="id_stock" id="id_stock" value="{$id}" />
			<div class="form-group">
				<label for="product" class="col-sm-2 control-label">Produto
					*</label>
				<div class="col-sm-5">
					<input type="text" autocomplete="off" name="product"
						id="product" class="form-control"
						placeholder="Digite a descri&ccedil;&atilde;o do produto..."
						required="required" />
				</div>
			</div>
			<div class="form-group">
				<label for="qtd" class="col-sm-2 control-label">Quantidade</label>
				<div class="col-sm-5">
					<input type="number" id="quantity_movement"
						name="quantity_movement" class="form-control"
						placeholder="Digite a quantidade de entrada..."
						required="required">
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