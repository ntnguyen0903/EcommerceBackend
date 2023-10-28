<!DOCTYPE html>
<html>
<head>
    <title>Kích hoạt tài khoản</title>
</head>
<body>
    <h2>Xin chào {{ $emailData['name'] }},</h2>
    <p>Cảm ơn bạn đã đăng ký tài khoản. Vui lòng nhấp vào liên kết bên dưới để kích hoạt tài khoản:</p>
    <a href="{{ url('/activate-account/'.$emailData['activation_token']) }}">Kích hoạt tài khoản</a>
</body>
</html>