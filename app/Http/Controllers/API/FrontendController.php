<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Orderitems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    // lấy tất cả sản phẩm
    public function index()
    {
        $product = Product::where('status', '0')->get();

        if ($product->count() > 0) {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'No products found.',
        ]);
    }
    
    //  sản phẩm bán  chạy 10
    public function indexbestseller()
    {
        $products = Product::select('products.*', DB::raw('SUM(orderitems.qty) as total_quantity'))
            ->join('orderitems', 'products.id', '=', 'orderitems.product_id')
            ->where('status', '0') 
            ->groupBy('products.id')
            ->orderByDesc('total_quantity')
            ->limit(10)->get();
        if ($products->count() > 0) 
        {
            return response()->json(['status' => 200, 'products' => $products,]);
        }
        return response()->json(['status' => 404, 'message' => 'No products found.']);
    }
    //  sản phẩm bán  mới 4 sản phẩm
    public function indexnew()
    {
        $products = Product::where('status', '0')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->select('id', 'name', 'selling_price','slug', 'original_price', 'image','qty', 'description')
            ->get();

        if ($products->count() > 0) {
            return response()->json([
                'status' => 200,
                'product' => $products,
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => 'No products found.',
        ]);
    }
    //tất cả sản phẩm có trạng thái 0
    public function viewproduct($category_slug, $product_slug)
    {
        $category = Category::where('slug', $category_slug)->where('status', '0')->first();
        if ($category) {
            $product = Product::where('category_id', $category->id)
                ->where('slug', $product_slug)
                ->where('status', '0')
                ->first();
            if ($product) {
                return response()->json([

                    'status' => 200,
                    'product' => $product,


                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "Không Có Sản Phẩm",
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Không tìm thấy danh mục",
            ]);
        }
    }
    public function category()
    {
        $category = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }
    public function product($slug)
    {
        $category = Category::where('slug', $slug)->where('status', '0')->first();
        if ($category) {
            $product = Product::where('category_id', $category->id)->where('status', '0')->get();
            if ($product) {
                return response()->json([
                    'status' => 200,
                    'product_data' => [
                        'product' => $product,
                        'category' => $category,
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => "Không Có Sản Phẩm",
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Không tìm thấy danh mục",
            ]);
        }
    }
    //tìm kiếm sp
  
    public function search(Request $request)
    {
        $term = $request->input('term');
      
    
        $query = Product::where(function ($query) use ($term)
         {
            $query->where('name', 'like', "%$term%")
                ->orWhere('selling_price', 'like', "%$term%")
                ->orWhere('os', 'like', "%$term%")
                ->orWhere('ram', 'like', "%$term%");

        });

        $results = $query->get();
    
        return response()->json([
            'results' => $results,
        ]);
    }
    ///tất cả đơn hang
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
                'message' => 'Đăng nhập để xem dữ liệu đơn hàng',
            ]);
        }
    }
     ///tất cả đơn hang đang xử lý
     public function vieworderuserstatus0()
     {
         if (auth('sanctum')->check()) {
             $user_id = auth('sanctum')->user()->id;
             $cartitem = Order::with('user', 'orderitems')
                 ->where('user_id', $user_id)
                 ->where('status', 'Đang xử lý')
                 ->get();
                 
             return response()->json([
                 'status' => 200,
                 'cart' => $cartitem,
             ]);
         } else {
             return response()->json([
                 'status' => 401,
                 'message' => 'Đăng nhập để xem dữ liệu đơn hàng',
             ]);
         }
     }
     //  đã  xác nhận
     public function vieworderuserstatus5()
     {
         if (auth('sanctum')->check()) {
             $user_id = auth('sanctum')->user()->id;
             $cartitem = Order::with('user', 'orderitems')
                 ->where('user_id', $user_id)
                 ->where('status', 'Đã xác nhận')
                 ->get();
                 
             return response()->json([
                 'status' => 200,
                 'cart' => $cartitem,
             ]);
         } else {
             return response()->json([
                 'status' => 401,
                 'message' => 'Đăng nhập để xem dữ liệu đơn hàng',
             ]);
         }
     }
      ///Đơn Hàng Đang giao
      public function vieworderuserstatus1()
      {
          if (auth('sanctum')->check()) {
              $user_id = auth('sanctum')->user()->id;
              // $cartitem = Order::where('user_id', $user_id)->get();
            //   $cartitem = Order::with('user', 'orderitems')->where('user_id', $user_id)->where('status', 1)->get();
            $cartitem = Order::with('user', 'orderitems')
            ->where('user_id', $user_id)
            ->where('status', 'Đang giao') // Thay đổi điều kiện trạng thái thành 'Đã Đặt'
            ->get();
              return response()->json([
                  'status' => 200,
                  'cart' => $cartitem,
              ]);
          } else {
              return response()->json([
                  'status' => 401,
                  'message' => 'Đăng nhập để xem dữ liệu  đơn hàng',
              ]);
          }
      }
       ///Đơn Hàng Đã giao
       public function vieworderuserstatus2()
       {
           if (auth('sanctum')->check()) {
               $user_id = auth('sanctum')->user()->id;

               // $cartitem = Order::where('user_id', $user_id)->get();
             //   $cartitem = Order::with('user', 'orderitems')->where('user_id', $user_id)->where('status', 1)->get();
             $cartitem = Order::with('user', 'orderitems')
             ->where('user_id', $user_id)
             ->where('status', 'Đã giao') // Thay đổi điều kiện trạng thái thành 'Đã Đặt'
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
        ///Đơn Hàng Đã hủy
        public function vieworderuserstatus3()
        {
            if (auth('sanctum')->check()) {
                $user_id = auth('sanctum')->user()->id;
                // $cartitem = Order::where('user_id', $user_id)->get();
              //   $cartitem = Order::with('user', 'orderitems')->where('user_id', $user_id)->where('status', 1)->get();
              $cartitem = Order::with('user', 'orderitems')
              ->where('user_id', $user_id)
              ->where('status', 'Đã huỷ') // Thay đổi điều kiện trạng thái thành 'Đã Đặt'
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
}
