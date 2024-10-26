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
            background-image: url('/images_dept/Homee.png');
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            justify-content: center; 
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden; /* Prevent overflow */
        }

        .site-title {
            font-size: 2em; 
            color: navy; /* Navy blue color for SEA */
            position: absolute; 
            top: 20px; 
            left: 20px; 
        }

        .site-title span {
            color: white; /* White color for ssist */
        }

        .reset-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 10px; /* Space above the form */
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent background */
            backdrop-filter: blur(10px); /* for glassmorphism */
            border-radius: 15px; 
            padding: 50px; /* padding for a larger form */
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); 
            position: absolute;
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%);
            width: 90%; /* Responsive width */
            max-width: 400px; /* Max width for larger screens */
        }

        .logo {
            width: 100px;
            height: auto;
            position: relative;
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }

        .reset-form input {
            padding: 10px;
            margin: 10px 0; 
            border: none;
            border-radius: 5px;
            width: 100%; 
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
        }

        .reset-button {
            padding: 10px 20px;
            background-color: navy; 
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .reset-button:hover {
            background-color: darkblue; 
        }

        .carousel {
            position: absolute;
            bottom: 20px;
            width: 100%;
            overflow: hidden; /* Hides overflow */
            display: flex;
            justify-content: center;
        }

        .carousel-inner {
            display: flex;
            animation: scroll 20s linear infinite; /* Continuous animation */
        }

        .carousel-image {
            width: 150px; /* Fixed width */
            height: 150px; /* Fixed height */
            object-fit: cover; 
            object-position: center; 
            flex-shrink: 0; 
            margin-right: 10px; 
            border-radius: 8px; 
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        .chatbot-container {
            position: absolute;
            top: 20px; 
            right: 20px; 
            z-index: 1000; 
        }

        .chatbot-button {
            background-color: navy; 
            color: white;
            border: none;
            border-radius: 50%; 
            width: 50px; 
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .chatbot-button:hover {
            background-color: darkblue; 
        }

        .chatbot-popup {
            display: none; 
            position: absolute;
            top: 60px;
            right: 0;
            width: 300px; 
            background: rgba(255, 255, 255, 0.2); 
            backdrop-filter: blur(15px); 
            border-radius: 10px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            z-index: 1000; 
        }

        .chatbot-header {
            background-color: rgba(0, 0, 128, 0.8);
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 10px 10px 0 0; 
        }

        .chatbot-messages {
            max-height: 200px; 
            overflow-y: auto; 
            padding: 10px;
            background: rgba(255, 255, 255, 0.1); 
        }

        .chatbot-input {
            width: calc(100% - 100px); 
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
            background: rgba(255, 255, 255, 0.8); 
        }

        .send-button {
            padding: 10px 20px;
            background-color: navy;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px; 
        }

        .chatbot-messages {
            max-height: 200px; 
            overflow-y: auto; 
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px); 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); 
        }

        .chatbot-message {
            padding: 8px 12px; 
            border-radius: 20px; 
            margin: 5px 0; 
            max-width: 80%; 
        }

        .user-message {
            background: rgba(0, 123, 255, 0.7);
            color: white; 
            align-self: flex-end;
        }

        .bot-message {
            background: rgba(255, 255, 255, 0.7); 
            color: black; 
            align-self: flex-start; 
        }

        @media (max-width: 768px) {
            .site-title {
                font-size: 1.5em; 
            }

            .reset-form {
                width: 90%; 
                padding: 30px; 
            }

            .carousel-image {
                width: 100px; 
                height: 100px;
            }
        }

        @media (max-width: 480px) {
            .site-title {
                font-size: 1.2em; 
            }

            .reset-form {
                padding: 20px; 
            }

            .carousel-image {
                width: 80px; 
                height: 80px;
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
        <div class="overlay"></div>
        <h2 class="site-title"><span>SEA</span><span style="color: white;">assist</span></h2>
        
        <!-- Password Reset Form -->
        <div class="reset-form">
            <h2>{{ __('Reset Password') }}</h2>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required readonly autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>                

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="reset-button">
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>
            </form>
        </div>



        <!-- Chatbot Section -->
        <div class="chatbot-container">
            <button class="chatbot-button" onclick="toggleChatbot()">🤖</button>
            <div class="chatbot-popup" id="chatbotPopup">
                <div class="chatbot-header">Chatbot</div>
                <div class="chatbot-messages" id="chatbotMessages"></div>
                <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type a message..." />
                <button class="send-button" onclick="sendMessage()">Send</button>
            </div>
        </div>
    </div>

    <script>
        function toggleChatbot() {
            const popup = document.getElementById('chatbotPopup');
            popup.style.display = (popup.style.display === 'block') ? 'none' : 'block';
        }

        function sendMessage() {
            const input = document.getElementById('chatbotInput');
            const message = input.value;
            if (message) {
                const messagesContainer = document.getElementById('chatbotMessages');
                const userMessage = document.createElement('div');
                userMessage.className = 'chatbot-message user-message';
                userMessage.textContent = message;
                messagesContainer.appendChild(userMessage);
                input.value = '';
            }
        }
    </script>
</body>
</html>
