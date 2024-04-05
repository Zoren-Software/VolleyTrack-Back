<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criação de Senha</title>
    <!-- Adicione o CSS do Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-panel">
                    <div class="card-header">CRIAR SENHA</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.set', ['token' => $token, 'tenant' => $tenant]) }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" disabled value="{{$user->email}}">
                            </div>
                            <div class="form-group">
                                <label for="password">Senha</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="password-confirm">Confirme a Senha</label>
                                <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Definir Senha</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<style>
    .btn-primary {
        background-color: #FF4E1B;
        border-color: #FF4E1B;
    }
    .btn-primary:hover {
        background-color: #FF4E1B;
        border-color: #FF4E1B;
        opacity: 0.85;
    }
    .login-panel {
            border: 1px solid #ccc; /* Borda geral - ajuste conforme sua preferência */
            border-top: 4px solid #FF4E1B; /* Borda superior */
            margin: auto;
            border-radius: 4px; /* Arredondamento das bordas */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Sombra opcional */
        }
</style>
</html>