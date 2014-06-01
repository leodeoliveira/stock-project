
<div class="col-md-offset-2">
	<div id="form-trans">
		<form role="form" class="form-horizontal" action="adm/user.put"
			method="post">
			<div class="form-group">
				<label for="name" class="col-sm-2 control-label">Nome *</label>
				<div class="col-sm-5">
					<input type="name" id="name" name="name" class="form-control"
						placeholder="Digite seu nome..." required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="email" class="col-sm-2 control-label">Email *</label>
				<div class="col-sm-5">
					<input type="email" id="email" name="email" class="form-control"
						placeholder="Digite seu email..." required="required">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">Senha *</label>
				<div class="col-sm-5">
					<input type="password" id="password" name="password"
						class="form-control" placeholder="Digite seu telefone...">
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
