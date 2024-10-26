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
            background-image: url('images_dept//Homee.png');
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

        .login-form {
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

        /* Logo image */
        .logo {
            width: 100px;
            height: auto;
            position: relative;
            animation: rotate 10s linear infinite;
        }

        /* Animation for rotating logo */
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotateY(360deg);
            }
        }

        .login-form input {
            padding: 10px;
            margin: 10px 0; 
            border: none;
            border-radius: 5px;
            width: 100%; 
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
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

        /* Carousel section */
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

        /* Image size and spacing */
        .carousel-image {
            width: 150px; /* Fixed width */
            height: 150px; /* Fixed height */
            object-fit: cover; /* Ensures the image fills the box and maintains its aspect ratio */
            object-position: center; /* Centers the image within its container */
            flex-shrink: 0; /* Prevents images from shrinking */
            margin-right: 10px; /* Space between images */
            border-radius: 8px; /* Optional: adds rounded corners */
        }

        /* Animation to create the infinite scroll effect */
        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-100%);
            }
        }

        /* Chatbot */
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
            width: 300px; /* Width of the popup */
            background: rgba(255, 255, 255, 0.2); /* Semi-transparent white */
            backdrop-filter: blur(15px); /* Blur effect for glassmorphism */
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
            overflow-y: auto; /* Automatically scroll when overflow */
            padding: 10px;
            background: rgba(255, 255, 255, 0.1); /* Background for messages */
        }

        .chatbot-input {
            width: calc(100% - 100px); /* Width for the input field */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent input */
        }

        .send-button {
            padding: 10px 20px;
            background-color: navy;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 5px; /* Space between input and button */
        }

        /* Chatbot messages styling */
        .chatbot-messages {
            max-height: 200px; 
            overflow-y: auto; 
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px); /* Blur effect */
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); 
        }

        .chatbot-message {
            padding: 8px 12px; /* Padding for messages */
            border-radius: 20px; /* Rounded corners */
            margin: 5px 0; /* Space between messages */
            max-width: 80%; /* Max width of a message */
        }

        /* User message styling */
        .user-message {
            background: rgba(0, 123, 255, 0.7);
            color: white; 
            align-self: flex-end;
        }

        /* Bot message styling */
        .bot-message {
            background: rgba(255, 255, 255, 0.7); 
            color: black; 
            align-self: flex-start; 
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .site-title {
                font-size: 1.5em; /* Smaller title on mobile */
            }

            .login-form {
                width: 90%; /* Full width on mobile */
                padding: 30px; /* Reduce padding */
            }

            .carousel-image {
                width: 100px; /* Smaller images on mobile */
                height: 100px;
            }
        }

        @media (max-width: 480px) {
            .site-title {
                font-size: 1.2em; /* Even smaller title on smaller screens */
            }

            .login-form {
                padding: 20px; /* Further reduce padding */
            }

            .carousel-image {
                width: 80px; /* Even smaller images */
                height: 80px;
            }

            .chatbot-button {
                width: 40px; /* Smaller chatbot button */
                height: 40px;
                font-size: 20px; /* Smaller font */
            }
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="background-image"></div>
        <div class="overlay"></div>
        <h2 class="site-title"><span>SEA</span><span style="color: white;">ssist</span></h2>
        
        <!-- Laravel Password Reset Form -->
        <div class="login-form">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="login-button"> {{ __('Email Password Reset Link') }}</button>
                       
                   
                </div>
            </form>
        </div>

        <!-- Carousel Section -->


        <!-- Carousel Script -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const carouselInner = document.querySelector(".carousel-inner");
                const images = Array.from(carouselInner.children);
                const cloneCount = 3;

                // Clone images to create a continuous effect
                for (let i = 0; i < cloneCount; i++) {
                    images.forEach(image => {
                        const clone = image.cloneNode(true);
                        carouselInner.appendChild(clone);
                    });
                }
            });
        </script>
    </div>
</body>
</html>
