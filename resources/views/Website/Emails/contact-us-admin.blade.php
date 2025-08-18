<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact-Us Query Received</title>
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
        margin: 30px auto;
        background-color: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.863) !important;
      ">
        <tr>
            <td
                style="
            padding: 20px;
            text-align: center;
            background-color: #E59041;
            color: #fff;
          ">
                <img src="{{ $url . '/' . $logo }}" alt="Sydney Quality Meat" style="width: 35%;">
                <h1 style="font-size: 25px; color: white;">Contact-Us Query Received</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px">
                <div style="text-align: center; padding: 20px 30px;">
                    <span class="name" style="display: block; font-weight: 600; font-size: 1.9rem; color:#333;">
                        Hello Support
                    </span>
                    <h1 style="font-size: 16px; font-weight: 400; color: #333;">
                        A Latest Contact us query recieved
                    </h1>
                </div>
                <div>
                    <p style="font-size: 16px; color: #333; margin-bottom: 2px;"><span style="font-size: 18px; font-weight:bold; color:rgba(0, 0, 0, 0.863);">From: </span> Mr/Mrs {{ $name ?? ' ' }}</p>
                    <p style="font-size: 16px; color: #333; margin-bottom: 2px;"><span style="font-size: 18px; font-weight:bold; color:rgba(0, 0, 0, 0.863);">Email: </span> {{ $email ?? ' ' }}</p>
                    <p style="font-size: 18px; color: black; font-weight:bold; margin-bottom: 2px;">Message</p>
                    <p style="font-size: 16px; color: #333; margin-bottom: 2px;">{{ $contact_message }}</p>
                </div>
            </td>
        </tr>
        <tr>
            <td
                style="
            padding: 20px;
            background-color: #E59041;
            color: #fff;
            text-align: center;
          ">
                <p>&copy; 2025 {{ config('app.website_name') }}. All Rights Reserved.</p>
            </td>
        </tr>
    </table>
</body>

</html>
