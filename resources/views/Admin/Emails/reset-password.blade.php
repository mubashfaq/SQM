<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password OTP</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: "Montserrat", sans-serif;
        }

        .email-container {
            width: 600px;
            margin: auto;
            border-radius: 10px !important;
            overflow: hidden;
        }

        .header {
            text-align: center;
            padding: 10px 0;
            color: #fff;
        }

        .content {
            text-align: center;
            padding: 20px;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            margin-top: 20px;
            background-color: #f2f2f2;
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            letter-spacing: 2px;
        }

        .footer {
            padding: 15px;
            background-color: #A42125;
            text-align: center;
            color: #fff;
            font-weight: 500;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header" >
        <img src="https://sydneyqualitymeats.com.au/WebsiteAssets/assets/img/logo-dark.png" alt="Website Logo" width="35%">
        <h2 style="padding: 10px 0; margin: 0; color: white; background-color: #A42125;">Reset Password OTP</h2>
    </div>
    <div class="content">
        @if (isset($details) && !empty($details))
            <h3 style="font-weight: 600; font-size: 20px; margin-bottom: 5px;">Hello, {{ $details->name }}</h3>
        @endif
        <p style="margin-bottom:20px; font-size: 14px; font-weight: 500;">
            Use the following OTP to reset your password:
        </p>
        @if (isset($otp))
            <div class="otp-code">{{ $otp }}</div>
        @else
            <p style="color: red;">OTP not available.</p>
        @endif
        <p style="margin-top: 30px; font-size: 14px; font-weight: 500;">
            If you did not request a password reset, please ignore this email.
        </p>
    </div>
    <div class="footer">
        <p>© 2025 Sydney Quality Meats. All Rights Reserved.</p>
    </div>
</div>
</body>
</html>
