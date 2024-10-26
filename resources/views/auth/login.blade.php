<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEAassist</title>
    <link rel="stylesheet" href="assets/css/landingpage.css">
    <link rel="icon" href="{{ asset('SEAassistLogo/SEALOGO.png') }}" type="image/png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .container {
            background-image: url('images_dept/Homee.png');
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            justify-content: center; 
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .site-title {
            font-size: 2em; 
            color: navy; 
            position: absolute; 
            top: 20px; 
            left: 20px;
        }

        .site-title span {
            color: white;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px; 
            padding: 50px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
        }

        .logo {
            width: 100px;
            height: auto;
        }

        .login-form input {
            padding: 10px;
            margin: 10px 0; 
            border: none;
            border-radius: 5px;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
        }

        .login-button {
            padding: 10px 20px;
            background-color: navy;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: darkblue;
        }

        .remember-me {
            margin-top: 10px;
            text-align: center;
        }

        .remember-me label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.9em;
        }

        .forgot-password {
            margin-top: 10px;
            font-size: 0.9em;
            color: white;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .site-title {
                font-size: 1.5em;
            }

            .login-form {
                width: 90%;
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .site-title {
                font-size: 1.2em;
            }

            .login-form {
                padding: 20px;
            }

            .chatbot-button {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <h2 class="site-title"><span>SEA</span><span style="color: white;"><a href=""></a>assist</span></h2>
        
        <!-- Laravel Login Form -->
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            <img src="images_dept/newlogo.png" alt="PCU Logo" class="logo">

            <!-- Email Input -->
            <input type="text" id="email" name="email" placeholder="Email" required autofocus value="{{ old('email') }}">
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror

            <!-- Password Input -->
            <input type="password" id="password" name="password" placeholder="Password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
{{-- 
            <!-- Remember Me Option -->
            <div class="remember-me">
                <label>
                    <input type="checkbox" name="remember"> Remember me
                </label>
            </div> --}}

            <!-- Login Button -->
            <button type="submit" class="login-button">Login</button>

            <!-- Forgot Password Link -->
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot your password?</a>
            @endif
        </form>
    </div>
</body>
</html>
