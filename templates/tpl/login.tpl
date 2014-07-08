<div class="container">
    <div class="row">
        <div class="col-md-offset">
            <h1 class="text-center login-title">Entre com usu&aacute;rio e senha</h1>
            <div class="account-wall">
                <form class="form-signin" action="login.put" method="post">
                <input type="text" name="email" class="form-control" placeholder="E-mail" required autofocus>
                <input type="password" name="password" class="form-control" placeholder="Senha" required>
				<span>{$message}</span>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>