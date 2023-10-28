<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        $productCount = $products->count();
        $totalQty = $products->sum('qty');
        return response()->json([
            'status' => 200,
            'productCount' => $productCount,
            'products' => $products,
            'totalQty' => $totalQty,
        ]);
    }
    //

    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'KHÔNG tìm thấy sản phẩm',
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'ram' => 'required|max:191',
            'os' => 'required|max:191',
            'brand' => 'required|max:20',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:10',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ]);
        } else {

            $product = Product::find($id);
            if ($product) {


                $product->category_id = $request->input('category_id');
                $product->slug = $request->input('slug');
                $product->name = $request->input('name');
                $product->description = $request->input('description');

                $product->ram = $request->input('ram');
                $product->os = $request->input('os');
               

                $product->brand = $request->input('brand');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->qty = $request->input('qty');


                if ($request->hasFile('image')) {
                    $path = $product->image;
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $fileName = time() . '.' . $extension;
                    $file->move('uploads/product/', $fileName);
                    $product->image = 'uploads/product/' . $fileName;
                }
                
                $product->status = $request->input('status');
                $product->update();
                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật sản phẩm thành công',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Cập nhật sản phẩm thất bại',
                ]);
            }
        }
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191|unique:products',
            'ram' => 'required|max:191',
            'os' => 'required|max:191',
            'brand' => 'required|max:20',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:10',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ]);
        } else {

            $product = new Product;
            $product->category_id = $request->input('category_id');
            $product->slug = $request->input('slug');
            $product->name = $request->input('name');
            $product->description = $request->input('description');

            $product->ram = $request->input('ram');
            $product->os = $request->input('os');
        

            $product->brand = $request->input('brand');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->qty = $request->input('qty');


            if ($request->hasFile('image')) {

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $file->move('uploads/product/', $fileName);
                $product->image = 'uploads/product/' . $fileName;
            }
            
            $product->status = $request->input('status') == true ? '1' : '0';
            // $category->status = $request->input('status') === '1' ? '1' : '0';
            $product->save();

            return response()->json([
                'status' => 200,
                'message' => 'Thêm sản phẩm thành công!!!',
            ]);
        }
    }
    public function destroy($id)
    {
            try {
                $product = Product::find($id);
                if ($product) {
                    $product->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Xóa sản phẩm thành công',
                    ]);
                } 
            } catch (QueryException $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể  sản phẩm có đơn hàng liên quan',
                ]);
            }
    }
}
