<!-- resources/views/emails/welcome_user.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to {{ config('app.name') }}!</title>
</head>
<body>
<h1>Welcome, {{ $user->name }}!</h1>

<p>We're excited to have you on board. Dive into our features, connect with others, and make the most of your experience.</p>

<p>Explore now:
    <a href="{{ config('app.url') }}">
        {{ config('app.name') }}
    </a>
</p>
<p>If you have any questions, our Help Center is here for you.</p>

<p>Happy exploring!</p>

<p>Best,<br>
    {{ config('app.name') }} Team</p>
</body>
</html>
