<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Updated Login Details</title>
    <style>
        .flexClassBetween {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
        }

        h1 {
            color: #1c1c1c;
            font-size: 24px;
            margin-top: 0;
        }

        .width70 {
            width: 70% !important;
        }

        .width30 {
            width: 30% !important;
        }

        .width100 {
            width: 100% !important;
        }
    </style>
</head>

<body
    style="
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    ">
    <table align="center" cellpadding="0" cellspacing="0" width="600"
        style="
        border-collapse: collapse;
        margin: 30px auto;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.863);
      ">
        <tr>
            <td
                style="
            padding: 10px 0px 0px 0px;
            text-align: center;
            color: #fff;
          ">
                <img src="https://sydneyqualitymeats.com.au/WebsiteAssets/assets/img/logo-dark.png" alt="Website Logo"
                width="35%">
                <h1 style="font-size: 28px; padding: 10px 0px; color: white; margin: 0px; background-color: #A42125;">Updated Login Details</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px">
                <div style="text-align: center; padding: 20px 30px;">
                    <span class="name"
                        style="display: block; color: black; font-weight: 600; font-size: 1.4rem; margin-bottom: 4px;">
                        @if (isset($name) && !empty($name))
                            Hello, {{ $name }}
                        @endif
                    </span>
                    <br>
                    <h1 style="font-size: 0.8rem; font-weight: 600; color: #333;">These are the new Login Details for
                        your account.</h1>
                    <hr>
                    <p style="text-align: start !important;">Email: <a href="mailto:{{ $email }}" style="color: black;">{{ $email }}</a></p>
                    <p style="text-align: start !important;">Password: {{ $password }}</p>
                    <hr>
                    <p style="color:rgb(0, 0, 0); font-size:12px">Please don't share your login details with anyone, and keep
                        this email only protected to you.'</p>

                </div>
                <div style="text-align: center; padding: 1rem 2rem;">
                    <p><a href="{{ $url }}" class="button"
                            style="padding: 10px 25px; font: inherit; background: linear-gradient(to right, #A42125, #A42125); border-radius: 50px; border: 0; color: #fff; margin: auto; box-shadow: 0 5px 10px rgba(0, 0, 0, .1); text-decoration: none;">
                            Login Account
                        </a>
                    </p>
                </div>
            </td>
        </tr>
        <tr>
            <td
                style="
            padding: 20px;
            background-color: #A42125;
            color: white;
            text-align: center;
          ">
                <p>&copy; 2025 {{ config('app.website_name') }}. All Rights Reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
