<!-- resources/views/auth/register.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="form-wrapper">
            <h1 class="page-heading">Bug Tracking System</h1>
            <h2>Create an Account</h2>
            @if (session('status'))
                <p class="status-message">{{ session('status') }}</p>
            @endif
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <p class="error-message">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <p class="error-message">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    @error('password') <p class="error-message">{{ $message }}</p> @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input type="password" id="password-confirm" name="password_confirmation" class="form-control" required>
                </div>

                
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select id="user_type" name="user_type" class="form-control" required>
                        <option value="developer">Developer</option>

                        <option value="manager">Manager</option>

                        <option value="QA">QA</option>
                    </select>
                </div>
                
            
                <button type="submit" class="submit-button">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
