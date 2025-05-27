<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Notification - Egybin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.6;
            color: #333333;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777777;
            text-align: center;
        }
        .footer a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Branch Notification - Egybin</h1>
        </div>
        <div class="content">
            <p>{{ $notificationMessage }}</p>
        </div>
        <div class="footer">
            <p>Thank you for being a valued customer of Egybin.</p>
            <p>If you have any questions, feel free to contact us at <a href="mailto:support@egybin.com">support@egybin.com</a>.</p>
        </div>
    </div>
</body>
</html>
