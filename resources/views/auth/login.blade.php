<!-- resources/views/auth/login.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h1 class="page-heading">Bug Tracking System</h1>
            <h2>Login</h2>
            @if (session('status'))
                <p class="status-message">{{ session('status') }}</p>
            @endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    @error('email') <p class="error-message">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password') <p class="error-message">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="submit-button">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
