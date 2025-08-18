<!-- Save this file as resources/views/emails/test.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject ?? 'Email Test' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>I Read Quran</h1>
    </div>
    
    <div class="content">
        <h2>Hello, {{ $name }}!</h2>
        
        <p>{{ $message }}</p>
        
        <p>This email was sent on {{ $date }} to test the email functionality of our application.</p>
        
        <p>If you received this email, it means our email system is working correctly!</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} I Read Quran. All rights reserved.</p>
        <p>This is an automated test email. Please do not reply.</p>
    </div>
</body>
</html>