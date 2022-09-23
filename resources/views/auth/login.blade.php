@php
    use App\Http\Controllers\FooterController;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/user.css') }}" rel="stylesheet" type="text/css"> 

    </head>
    <body>
        <div class="main">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="action_logo"><a href="/"><img src="{{ asset('images/movies-icon.jpeg') }}" class="logo"/></a></div>
                <div class="action">{{ __('Login') }}</div>
                <div class="field">
                    <input id="username" type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required autocomplete="off" autofocus placeholder="Username">

                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
                <div class="field">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off" placeholder="Password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
                <div class="button">
                    <button class="btn btn-primary btn-sm btn-block" type="submit">Login</button>     
                </div>       
                <div class="field text">{{ __('New to') }} {{ env('APP_NAME') }}{{ __('?') }} <a href="{{ route('register') }}" class="only-text">{{ __('Join now') }}</a></div>
            </form>
        </div>
        <nav class="navbar fixed-bottom navbar-dark bg-dark">
            <div class="container justify-content-md-center">
                <span class="navbar-text">
                    &copy; {{ FooterController::show() }}
                </span>
            </div>
        </nav>
    </body>
</html>