<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;


class CheckoutController extends Controller
{
    //Xử  lý  thiếu ràng buộc
    // public function placeorder(Request $request)
    // {
    //     if (auth('sanctum')->check()) {
    //         $validator = Validator::make($request->all(), [
    //             'firstname' => 'required|max:191',
    //             'lastname' => 'required|max:191',
    //             'email' => 'required|max:191',
    //             'phone' => 'required|numeric',
    //             'address' => 'required|max:191',
    //             'city' => 'required|max:191',
    //             'state' => 'required|max:191',
    //             'zipcode' => 'required|max:191',

    //         ]);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 422,

    //                 'errors' => $validator->errors(),
    //             ]);
    //         } else {
    //             $user_id = auth('sanctum')->user()->id;
    //             $order = new Order;
    //             $order->user_id = $user_id;
    //             $order->firstname = $request->firstname;
    //             $order->lastname = $request->lastname;
    //             $order->phone = $request->phone;
    //             $order->email = $request->email;
    //             $order->address = $request->address;
    //             $order->city = $request->city;
    //             $order->state = $request->state;
    //             $order->zipcode = $request->zipcode;

    //             // $order->payment_mode = 'COD';
    //             $order->payment_mode = $request->payment_mode;
    //             $order->tracking_no = 'fundaecom' . rand(1111, 9999);
    //             $order->save();

    //             $cart = Cart::where('user_id', $user_id)->get();

    //             $orderitems = [];
    //             foreach ($cart as $item) {
    //                 $orderitems[] = [
    //                     'product_id' => $item->product_id,
    //                     'qty' => $item->product_qty,
    //                     'price' => $item->product->selling_price,
    //                 ];

    //                 $item->product->update([
    //                     'qty' => $item->product->qty - $item->product_qty
    //                 ]);
    //             }
                
    //             $order->orderitems()->createMany($orderitems); //  them vào table  order_detail
    //             // xoa gio  hàng
    //             Cart::destroy($cart);
    //             //
    //             // Mail::to($order->email)->send(new OrderPlaced($order));
    //             // Mail::to($order->email)->send(new OrderPlaced($order));
    //             Mail::to($order->email)->send(new OrderPlaced($order));
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Đặt hàng thành công',

    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => 401,
    //             'message' => 'Đăng nhập để tiếp tục',
    //         ]);
    //     }
    // }
    
    //test ràng  buộc
    public function placeorder(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'email' => 'required|max:191',
                'phone' => 'required|numeric',
                'address' => 'required|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                'zipcode' => 'required|max:191',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ]);
            } else {
                $user_id = auth('sanctum')->user()->id;
                $order = new Order;
                $order->user_id = $user_id;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->phone = $request->phone;
                $order->email = $request->email;
                $order->address = $request->address;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zipcode = $request->zipcode;
                
                $order->payment_mode = $request->payment_mode;
                $order->payment_id = $request->payment_id;
                
                $order->tracking_no = 'fundaecom' . rand(1111, 9999);
                $order->totalPrice = $request->totalPrice;
                $order->save();

                $cart = Cart::where('user_id', $user_id)->get();

                $orderitems = [];
                $invalidProducts = [];

                foreach ($cart as $item) {
                    if ($item->product->qty < $item->product_qty) {
                        $invalidProducts[] = [
                            'product_id' => $item->product_id,
                            'qty' => $item->product_qty,
                        ];
                    } else {
                        $orderitems[] = [
                            'product_id' => $item->product_id,
                            'qty' => $item->product_qty,
                            'price' => $item->product->selling_price,
                        ];

                        $item->product->update([
                            'qty' => $item->product->qty - $item->product_qty,
                        ]);
                    }
                }

                if (!empty($invalidProducts)) {
                    return response()->json([
                        'status' => 423,
                        'message' => 'Một số sản phẩm có số lượng không đủ.',
                        'invalidProducts' => $invalidProducts,
                    ]);
                }

                $order->orderitems()->createMany($orderitems);
                Cart::destroy($cart);

                Mail::to($order->email)->send(new OrderPlaced($order));

                return response()->json([
                    'status' => 200,
                    'message' => 'Đặt hàng thành công',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Đăng nhập để tiếp tục',
            ]);
        }
    }
     //đúng
    // public function placeorder(Request $request)
    // {
    //     if (auth('sanctum')->check()) {
    //         $validator = Validator::make($request->all(), [
    //             'firstname' => 'required|max:191',
    //             'lastname' => 'required|max:191',
    //             'email' => 'required|max:191',
    //             'phone' => 'required|numeric',
    //             'address' => 'required|max:191',
    //             'city' => 'required|max:191',
    //             'state' => 'required|max:191',
    //             'zipcode' => 'required|max:191',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status' => 422,
    //                 'errors' => $validator->errors(),
    //             ]);
    //         } else {
    //             $user_id = auth('sanctum')->user()->id;
    //             $cart = Cart::where('user_id', $user_id)->get();
    
    //             $invalidProducts = [];
    //             $orderitems = [];
    
    //             foreach ($cart as $item) {
    //                 if ($item->product->qty < $item->product_qty) {
    //                     $invalidProducts[] = [
    //                         'product_id' => $item->product_id,
    //                         'qty' => $item->product_qty,
    //                     ];
    //                 } else {
    //                     $orderitems[] = [
    //                         'product_id' => $item->product_id,
    //                         'qty' => $item->product_qty,
    //                         'price' => $item->product->selling_price,
    //                     ];
    //                 }
    //             }
    
    //             if (!empty($invalidProducts)) {
    //                 return response()->json([
    //                     'status' => 423,
    //                     'message' => 'Một số sản phẩm có số lượng không đủ.',
    //                     'invalidProducts' => $invalidProducts,
    //                 ]);
    //             }
    
    //             // Tạo đơn hàng chỉ khi không có sản phẩm nào không đủ số lượng
    //             $order = new Order;
    //             $order->user_id = $user_id;
    //             $order->firstname = $request->firstname;
    //             $order->lastname = $request->lastname;
    //             $order->phone = $request->phone;
    //             $order->email = $request->email;
    //             $order->address = $request->address;
    //             $order->city = $request->city;
    //             $order->state = $request->state;
    //             $order->zipcode = $request->zipcode;
    //             $order->payment_mode = $request->payment_mode;
    //             $order->payment_id = $request->payment_id;
    //             $order->tracking_no = 'fundaecom' . rand(1111, 9999);
    //             $order->totalPrice = $request->totalPrice;
    //             $order->save();
    
    //             $order->orderitems()->createMany($orderitems);
    //             Cart::destroy($cart);
    
    //             Mail::to($order->email)->send(new OrderPlaced($order));
    
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Đặt hàng thành công',
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => 401,
    //             'message' => 'Đăng nhập để tiếp tục',
    //         ]);
    //     }
    // }
   
    public function validateOrder(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'email' => 'required|max:191',
                'phone' => 'required|numeric',
                'address' => 'required|max:191',
                'city' => 'required|max:191',
                'state' => 'required|max:191',
                'zipcode' => 'required|max:191',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,

                    'errors' => $validator->errors(),
                ]);
            }
             else 
            {
                return response()->json([
                    'status' => 200,
                    'message' => 'Biểu mẫu được xác thực thành công',

                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Đăng nhập để tiếp tục',
            ]);
        }
    }
}
