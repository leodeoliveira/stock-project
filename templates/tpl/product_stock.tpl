<div class="col-md-offset-2">
<div id="form-trans">
<form role="form" class="form-horizontal" action="adm/user.put" method="post">
	<div class="form-group">
		<label for="product" class="col-sm-2 control-label">Produto *</label>
			<div class="col-sm-5">
				<select name="product" class="form-control">
								<option value="1">Produto 1</option>
								<option value="2">Produto 2</option>
								<option value="3">Produto 3</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="qtd" class="col-sm-2 control-label">Quantidade</label>
			<div class="col-sm-5">
				<input type="text" id="qtd" name="qtd" class="form-control" placeholder="Digite o valor unitário..." required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="note" class="col-sm-2 control-label">Observações *</label>
			<div class="col-sm-5">
				<textarea type="textarea" id="note" name="note" class="form-control" placeholder="Digite as informações adicionais..."></textarea>
			</div>
		</div>
			<div class="form-group">
				<div class="col-sm-6 pull-right">
					<button class="btn btn-success" type="submit">
						Salvar
					</button>
				</div>
			</div>
		</form>
	</div>
	{$tabela}
</div>
