<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created</title>
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #F4F6FA;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            width: 100%;
        }

        .container {
            width: 100%;
            max-width: 600px;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 8px;
            margin: 40px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #0056D2;
            font-weight: 700;
        }

        .content {
            font-size: 16px;
            color: #333333;
            line-height: 1.8;
        }

        .content p {
            margin-bottom: 15px;
        }

        .content strong {
            color: #0056D2;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777777;
            margin-top: 30px;
            border-top: 1px solid #e0e0e0;
            padding-top: 10px;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #0056D2;
            text-decoration: none;
            font-weight: 600;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0056D2;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin-top: 20px;
            text-align: center;
        }

        .button:hover {
            background-color: #003d99;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to SEAassist</h1>
        </div>
        <div class="content">
            <p>Dear User,</p>
            <p>Your account has been successfully created. Below are your login details:</p>
            <p><strong>Email:</strong> {{$email}}</p>
            <p><strong>Password:</strong> {{$password}}</p>
            <p>For security reasons, please change your password after logging in for the first time.</p>
        </div>
        <div class="footer">
            <p>&copy; <span id="year"></span> SEAassist. All rights reserved.</p>
        </div>
    </div>

    <script>
        document.getElementById("year").textContent = new Date().getFullYear();
    </script>
</body>
</html>
