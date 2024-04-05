<form method="POST" action="{{ route('password.set', ['token' => $token, 'tenant' => $tenant]) }}">
    @csrf

    <input type="password" name="password" required>
    <input type="password" name="password_confirmation" required>

    <button type="submit">Definir Senha</button>
</form>

