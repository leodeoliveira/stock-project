<div class="col-md-offset-2">
<div id="form-trans">
<form role="form" class="form-horizontal" action="adm/user.put" method="post">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Descrição *</label>
			<div class="col-sm-5">
				<select name="estado" class="form-control">
								<option value="1">Produto 1</option>
								<option value="2">Produto 2</option>
								<option value="3">Produto 3</option>
							</select>
			</div>
		</div>
		<div class="form-group">
			<label for="document" class="col-sm-2 control-label">Quantidade</label>
			<div class="col-sm-5">
				<input type="document" id="document" name="email" class="form-control" placeholder="Digite o valor unitário..." required="required">
			</div>
		</div>
		<div class="form-group">
			<label for="cep" class="col-sm-2 control-label">Observações *</label>
			<div class="col-sm-5">
				<textarea type="textarea" id="cep" name="password" class="form-control" placeholder="Digite as informações adicionais..."></textarea>
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
</div>
