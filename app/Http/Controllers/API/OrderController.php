<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orderitems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function vieworderuser()
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            // $cartitem = Order::where('user_id', $user_id)->get();
            $cartitem = Order::with('user', 'orderitems')
            ->where('user_id', $user_id)
            ->orderByDesc('created_at')
            ->get();
            return response()->json([
                'status' => 200,
                'cart' => $cartitem,
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Đăng nhập để xem dữ liệu giỏ hàng',
            ]);
        }
    }
    public function index()
    {
        $order = Order::with('user', 'orderitems')
        ->orderByDesc('created_at')
        ->get();
        $allOrders = $order->count();
        return response()->json([
            'status' => 200,
            'orders' => $order,
            'allOrders'=>$allOrders,
        ]);
    }
    
    //13/07
    public function edit($id)
    {

        $category = Order::find($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy ID danh mục',
            ]);
        }
    }
    public function edit1($id)
    {
        $category = Order::with('orderitems')->find($id);
        
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy ID đơn hàng',
            ]);
        }
    }
    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'tracking_no' => 'required|max:255',
    //         'phone' => 'required|max:255',
    //         'email' => 'required|email|max:255',
    //         'status' => 'required|in:0,1',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 422,
    //             'errors' => $validator->errors(),
    //         ]);
    //     } else {
    //         $order = Order::find($id);

    //         if ($order) {
    //             $order->tracking_no = $request->input('tracking_no');
    //             $order->phone = $request->input('phone');
    //             $order->email = $request->input('email');
    //             $order->status = $request->input('status') === '1' ? '1' : '0';
    //             $order->save();

    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Sửa đơn hàng thành công',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => 'Không tìm thấy đơn hàng',
    //             ]);
    //         }
    //     }
    // }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
     
            'phone' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ]);
        } else {
            $order = Order::find($id);

            if ($order) {
                
                $order->phone = $request->input('phone');
                $order->email = $request->input('email');
                $order->status = $request->input('status');

                $order->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Sửa đơn hàng thành công',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Không tìm thấy đơn hàng',
                ]);
            }
        }
    }
    //  hủy  nhưng  chưa trả số lượng cho product
    // public function cancelOrder($id)
    // {
    //     $order = Order::findOrFail($id);

    //     // Kiểm tra xem đơn hàng có thể hủy hay không
    //     if ($order->status === 'Đã hủy') {
    //         return response()->json(['status' => 400, 'message' => 'Đơn hàng đã được hủy trước đó.']);
    //     }

    //     if ($order->status === 'Đã giao') {
    //         return response()->json(['status' => 400, 'message' => 'Không thể hủy đơn hàng đã giao.']);
    //     }

    //     // Thực hiện hủy đơn hàng
    //     $order->status = 'Đã hủy';
    //     $order->save();

    //     return response()->json(['status' => 200, 'message' => 'Hủy đơn hàng thành công.']);
    // }
    public function cancelOrder($id)
    {
            $order = Order::findOrFail($id);

             // Kiểm tra xem đơn hàng có thể hủy hay không
             if ($order->status === 'Đang giao') {
                return response()->json(['status' => 400, 'message' => 'Đơn hàng đã được đưa cho bên vận chuyển.']);
            }
            // Kiểm tra xem đơn hàng có thể hủy hay không
            if ($order->status === 'Đã hủy') {
                return response()->json(['status' => 400, 'message' => 'Đơn hàng đã được hủy trước đó.']);
            }

            if ($order->status === 'Đã giao') {
                return response()->json(['status' => 400, 'message' => 'Không thể hủy đơn hàng đã giao.']);
            }

            
            // Thực hiện hủy đơn hàng
            $order->status = 'Đã hủy';
            $order->save();
            // Trả lại số lượng sản phẩm
            foreach ($order->orderitems as $orderitem) {
                $product = $orderitem->product;
                $product->qty += $orderitem->qty;
                $product->save();
            }


            return response()->json(['status' => 200, 'message' => 'Hủy đơn hàng thành công.']);
    }
    
    public function countOrder()
    {
        
        $orders = Order::where('status', 'Đã giao')->get();
        $ordersCount = $orders->count();
        return response()->json([
            'status' => 200,
            'orders' => $orders,
            'ordersCount'=>$ordersCount,
        ]);


    }
   
    // tìm  order bằng  email
    // public function search(Request $request)
    // {
    //     $term = $request->input('term');
       
    //     $results = Order::where('tracking_no', 'like', "%$term%")
    //                       ->get();
    //     return response()->json([
    //         'results' => $results,
    //     ]);
    // }
    //
    public function search(Request $request)
    {
        $term = $request->input('term');

        $results = Order::where(function ($query) use ($term) {
            $query->where('status', 'like', "%$term%")
                ->orWhere('email', 'like', "%$term%");
        })->get();

        return response()->json([
            'results' => $results,
        ]);
    }

   // thống  kê  
//    public function statistics()
//     {
//         // Số lượng đơn hàng đã đặt
//         $totalOrders = Order::count();

//         // Tổng doanh thu
//         $totalRevenue = Order::sum('totalPrice');


//         // Thống kê sản phẩm
//         $productStatistics = Orderitems::select('product_id')
//             ->with('product')
//             ->selectRaw('SUM(qty) as totalQty,SUM(price) as totalSales')
//             ->groupBy('product_id')
//             ->get();

//         return response()->json([
//             'status' => 200,
//             'totalOrders' => $totalOrders,
//             'totalRevenue' => $totalRevenue,
//             'productStatistics' => $productStatistics,
//         ]);
//     }
    public function statistics()
    {
        $totalOrders = Order::where('status', 'Đã giao')->count();

        // Tổng doanh thu
        $totalRevenue = Order::where('status', 'Đã giao')->sum('totalPrice');

        // Thống kê sản phẩm đã bán
        $productStatistics = Orderitems::select('product_id')
            ->selectRaw('SUM(qty) as totalQty, SUM(price) as totalSales')
            ->whereIn('order_id', Order::where('status', 'Đã giao')->pluck('id')->toArray())
            ->groupBy('product_id')
            ->get();

        return [
            'status' => 200,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'productStatistics' => $productStatistics,
        ];
    }
    //tìm kiếm đơn hang
    public function placeOrder(Request $request)
    {
        // Validate dữ liệu được gửi từ frontend
        $validatedData = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'totalPrice' => 'required|numeric',
            'payment_mode' => 'required',
            'payment_id' => 'nullable',
        ]);

        // Lưu thông tin đơn hàng vào cơ sở dữ liệu
        $order = Order::create($validatedData);

        // Trả về phản hồi thành công
        return response()->json([
            'status' => 200,
            'message' => 'Order placed successfully',
            'order' => $order,
        ]);
    }
}
