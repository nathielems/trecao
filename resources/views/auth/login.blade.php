@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">{{ __('Login') }}</div>
        <div class="form">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" autocomplete="email" autofocus placeholder="Email">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="Senha">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                    <div class="remember-password">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>

                        <label class="form-check-label" for="remember">
                            {{ __('Lembrar senha') }}
                        </label>
                    </div>
                    <button type="submit">
                        {{ __('Entrar') }}
                    </button>
                </div>
                <div class="details">
                    @if (Route::has('password.request'))
                    <a class="forgot-password btn btn-link " href="{{ route('password.request') }}">
                        {{ __('Esqueceu a senha?') }}
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection