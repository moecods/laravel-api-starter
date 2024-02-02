<!-- resources/views/emails/verification_code.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verification Code for {{ $user->name }} from {{ config('app.name') }}!</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: auto;
            max-width: 600px;
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
            font-size: 16px;
        }

        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .footer {
            margin-top: 20px;
            color: #999;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Email Verification</h1>
    <p>Hi {{ $user->name }},</p>
    <p>Thank you for signing up! To complete your registration, please enter the following verification code:</p>

    <p class="verification-code">{{ $verificationCode }}</p>

    <p>If you didn't sign up for an account, please ignore this email.</p>

    <div class="footer">
        <p>Best Regards,<br>{{ config('app.name') }} Team</p>
    </div>
</div>
</body>
</html>
