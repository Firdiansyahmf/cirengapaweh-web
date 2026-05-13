<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Cireng A'paweh Admin</title>

    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <div class="loginContainer">
        <div class="bgShapeRed"></div>
        <div class="bgShapeYellow"></div>

        <div class="logoSection">
            <img src="{{ asset('assets/img/logo/logo.png') }}" alt="Cireng A'paweh Logo" class="logo" />
        </div>

        <div class="loginCard">
            <h2 class="loginTitle">Silakan log in untuk mengakses admin dashboard</h2>

            <form class="loginForm" id="loginForm" method="GET" action="{{ url('admin/dashboard') }}">
                @csrf

                <div class="formGroup">
                    <input type="email" id="email" name="email" placeholder="Masukkan Email Anda"
                        class="formInput" required />
                </div>

                <div class="formGroup">
                    <div class="passwordInputWrapper">
                        <input type="password" id="password" name="password" placeholder="Password..."
                            class="formInput passwordInput" required />
                        <button type="button" class="togglePasswordBtn" id="togglePasswordBtn">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="checkboxGroup">
                    <input type="checkbox" id="rememberMe" name="remember" class="checkbox" />
                    <label for="rememberMe" class="checkboxLabel">Ingat saya</label>
                </div>

                <button type="submit" class="loginButton">Log In</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/admin/login.js') }}"></script>
</body>

</html>
