<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact-Us Confirmation Email</title>
    <style>
        .flexClassBetween {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
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
        margin: 0 auto;
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
                <h1 style="font-size: 28px; padding: 10px 0px; color: white; margin: 0px; background-color: #A42125;">Confirmation</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px">
                <div style="text-align: center; padding: 0px 30px;">
                    <span class="name" style="display: block; color:black; font-weight: 600; font-size: 16px;">
                        @if (isset($name) && !empty($name))
                            Hello, <span style="color:black; font-weight: 600; font-size: 16px; text-transform: uppercase;">{{ $name }}</span>
                        @endif
                    </span>
                    <h1 style="font-size: 16px; font-weight: 600; color:black;">Thank You for Contacting Sydney Quality Meats</h1>
                    <p
                        style="font-size: 15px; color: slategrey; margin-top: 15px; text-align: start; line-height: 25px;">
                        We appreciate you taking the time to reach out and share your thoughts with us. We have received your message and one of our team members will get back to you as soon as possible.
                        <br><br>
                        In the meantime, if you have any additional information you'd like to share, or if you have any immediate questions, please don't hesitate to reply to this email.
                        <br><br>
                        We look forward to assisting you and will respond to your inquiry at our earliest convenience.<br>
                    </p>
                </div>
                <div style="text-align: center; padding: 2rem;">
                    <p><a href="{{ url('/shop')}}" class="button"
                        style="padding: 10px 25px; font: inherit; background: #a42125; border-radius: 50px; border: 0; color: #fff; margin: auto; box-shadow: 0 5px 10px rgba(0, 0, 0, .1); text-decoration: none;">
                        Explore
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
            color: #fff;
            text-align: center;
          ">
                <p>&copy; 2025 {{ config('app.website_name') }}. All Rights Reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
