<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Update Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            max-width: 240px;
            height: auto;
        }
        .title-bar {
            background-color: #A42125;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin: 0;
        }
        .content {
            padding: 30px;
        }
        .user-details {
            margin-bottom: 30px;
        }
        .user-meta {
            margin-bottom: 30px;
        }
        .footer {
            background-color: #A42125;
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #A42125;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin-top: 15px;
            font-weight: bold;
        }
        h1 {
            font-size: 28px;
            margin: 0;
        }
        h2 {
            color: #A42125;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            width: 30%;
        }
        .login-section {
            text-align: center;
            margin: 30px 0;
        }
        .thank-you {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://sydneyqualitymeats.com.au/WebsiteAssets/assets/img/logo-dark.png" alt="Sydney Quality Meats" class="logo">
        </div>
        
        <h1 class="title-bar">Account Update Confirmation</h1>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>Your account details have been successfully updated.</p>
            
            <div class="user-details">
                <h2>Account Information</h2>
                <table>
                    <tr>
                        <th>Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <th>Account Type</th>
                        <td>{{ $user->role_id == 5 ? 'Customer' : 'Admin/Staff' }}</td>
                    </tr>
                </table>
            </div>
       
            
            <!-- <div class="login-section">
                <p>You can log in to your account using the link below:</p>
                <a href="{{ $url }}" class="button">Login to Your Account</a>
            </div> -->
            
            <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
            
            <p class="thank-you">Thank you for choosing Sydney Quality Meats!</p>
        </div>
        
        <div class="footer">
            This email is auto-generated. Please do not reply to this Email.
        </div>
    </div>
</body>
</html>