<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.css">
    <title>Approved Email</title>
    <style>
        .email {
            width: 600px;
            margin: 50px auto;
            font-family: Arial, sans-serif;
        }

        .email-header {
            width: 100%;
            padding: 20px 0;
            text-align: center;
        }

        .email-body {
            padding: 0px 30px 30px 30px;
            background-color: #ffffff;
            color: #000000;
        }

        .email-body b {
            color: #000000;
        }

        .email-body a {
            color: #000000;
            text-decoration: none;
            font-weight: 600;
        }

        .email-footer {
            padding: 20px;
            background-color: #A42125;
            text-align: center;
            color: #ffffff;
        }

        .email-footer a {
            color: #ffffff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email">
        <div class="email-header">
            <img src="https://sydneyqualitymeats.com.au/WebsiteAssets/assets/img/logo-dark.png" alt="Website Logo"
                width="240px !important">
            <h1 style="font-size: 28px; padding: 10px 0px; color: white; margin: 0px; background-color: #A42125; text-align: center;">Dear @if (isset($name) && !empty($name))
                {{ $name }}
            @endif
            </h1>
        </div>
        <div class="email-body">
            <br>
            Welcome to Sydney Quality Meat Store! We’re thrilled to have you join our community.
            <br><br>
            <b>What’s Next?</b>
            <br><br>
            <b>Log In:</b> Use your credentials to log in and start exploring.<br>
            <b>Explore:</b> Discover all the features and benefits we offer.<br>
            <b>Get Started:</b> Begin your journey with Sydney Quality Meat make the most out of your
            experience.
            <br><br>
            <b>Your Account Details:</b>
            <br><br>
            <b>Username:</b>
            @if (isset($name) && !empty($name))
                {{ $name }}
            @endif
            <br>
            <b>Email:</b>
            @if (isset($email) && !empty($email))
                {{ $email }}
            @endif
            <br>
            <b>Password:</b>
            @if (isset($password) && !empty($password))
                {{ $password }}
            @endif
            <br><br>
            Thank you for joining us. We’re excited to see what you’ll achieve with Sydney Quality Meats Store!
        </div>
        <div class="email-footer">
            <div class="email-footer-bar1">
                <div class="footer-bar1-text1">This email is auto generated, don’t reply on this Email.</div>
            </div>
        </div>
    </div>

</body>

</html>