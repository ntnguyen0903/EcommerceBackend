{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đơn hàng</title>
    <style>
        /* Thiết lập các kiểu CSS để tạo giao diện email đẹp */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        p {
            margin-bottom: 20px;
        }

        .order-details {
            margin-top: 30px;
            border-collapse: collapse;
            width: 100%;
        }

        .order-details td, .order-details th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .order-details th {
            background-color: #f5f5f5;
            text-align: left;
        }

        .thank-you {
            margin-top: 30px;
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Xác nhận đơn hàng</h1>
    <p>Kính gửi {{ $order->firstname }} {{ $order->lastname }},</p>

    <p>Cảm ơn bạn đã đặt hàng từ chúng tôi. Chúng tôi xin thông báo rằng đơn hàng của bạn đã được đặt thành công.</p>

    <h2>Chi tiết đơn hàng</h2>
    <table class="order-details">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
            </tr>
          
        </thead>
        <tbody>
            @php
                $totalPrice = 0;
            @endphp
            @foreach ($order->orderitems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                </tr>
                @php
                    $totalPrice += $item->price;
                @endphp
            @endforeach
        </tbody>
    </table>
    <p>Ngày dự kiến giao hàng: {{ date('d/m/Y', strtotime('+5 days')) }}</p>
    <div class="thank-you">
        <p>Tổng giá trị đơn hàng: {{ number_format($totalPrice, 0, ',', '.') }}đ</p>
        <p>Cảm ơn bạn đã lựa chọn cửa hàng của chúng tôi. Nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ thêm, xin vui lòng liên hệ với chúng tôi.</p>
        <p>Trân trọng,</p>
        <p>Đội ngũ cửa hàng</p>
    </div>
</body>
</html> --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đơn hàng</title>
    <style>
        /* Thiết lập các kiểu CSS để tạo giao diện email đẹp */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        p {
            margin-bottom: 20px;
        }

        .order-details {
            margin-top: 30px;
            border-collapse: collapse;
            width: 100%;
        }

        .order-details td, .order-details th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .order-details th {
            background-color: #f5f5f5;
            text-align: left;
        }

        .thank-you {
            margin-top: 30px;
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Xác nhận đơn hàng</h1>
    <p>Kính gửi {{ $order->firstname }} {{ $order->lastname }},</p>

    <p>Cảm ơn bạn đã đặt hàng từ chúng tôi. Chúng tôi xin thông báo rằng đơn hàng của bạn đã được đặt thành công.</p>

    <h2>Chi tiết đơn hàng</h2>
    <table class="order-details">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Thành tiền</th>
            </tr>
          
        </thead>
        <tbody>
            @php
                $totalPrice = 0;
            @endphp
            @foreach ($order->orderitems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td>{{ number_format($item->price * $item->qty, 0, ',', '.') }}đ</td>
                </tr>
                @php
                    $totalPrice += $item->price * $item->qty;
                @endphp
            @endforeach
        </tbody>
    </table>
    <p>Ngày dự kiến giao hàng: {{ date('d/m/Y', strtotime('+5 days')) }}</p>
    <div class="thank-you">
        <p>Tổng giá trị đơn hàng: {{ number_format($totalPrice, 0, ',', '.') }}đ</p>
        <p>Cảm ơn bạn đã lựa chọn cửa hàng của chúng tôi. Nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ thêm, xin vui lòng liên hệ với chúng tôi.</p>
        <p>Trân trọng,</p>
        <p>Đội ngũ cửa hàng</p>
    </div>
</body>
</html>

