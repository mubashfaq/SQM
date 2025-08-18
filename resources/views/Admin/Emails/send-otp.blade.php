<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email Template</title>
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
        }

    </style>
</head>

<body>
<div class="email-container">
    <div style="text-align: center;background-color: #A42125;padding: 10px 0px;">
        <img src="https://yazi.com.au/assets/images/white-logo.png" alt="Website Logo" width="15%">
        <h2 style="margin-top: 10px;font-weight: 700;font-size: 24px;color: #fff;">Your OTP</h2>
    </div>
    <div style="text-align: center;">
        <h3 style="font-weight: 600;margin-top: 20px;font-size: 20px;">{{$name}}</h3>
        <p style="margin-top: 20px;font-size: 16px;">Your OTP for password reset is:</p>
        <div style="width: 100px;margin: 20px auto 0px;background-color: #A42125;padding: 10px;border-radius: 5px;">
            <h1 style="font-weight: 600;font-size: 20px;color: #fff;">{{$otp}}</h1>
        </div>
        <p style="margin-top: 20px;font-size: 14px;font-weight: 500;">If you did not request a password reset, you can safely ignore this email.</p>
    </div>
    <div style="padding: 15px;background-color: #A42125;margin-top: 20px;">
        <p style="text-align: center; color: #fff;font-weight: 500;font-size: 16px;">Call us at <a
                href="tel:0400023444" style="color: #fff;text-decoration: none;">0400023444</a> or reply to this
            Email</p>
    </div>
</div>
</body>

</html>
