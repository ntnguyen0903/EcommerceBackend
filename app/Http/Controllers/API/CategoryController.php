<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }
    // public function allcategory()
    // {
    //     try {
    //         $category = Category::where('status', '0')->get();
    //         return response()->json([
    //             'status' => 200,
    //             'category' => $category,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 500,
    //             'error' => $e->getMessage(),
    //         ]);
    //     }
    // }
    public function allcategory()
    {
        $category = Category::where('status', '0')->get();
        return response()->json([
            'status' => 200,
            'category' => $category,
        ]);
    }
    public function edit($id)
    {
        $category = Category::find($id);
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
    public function destroy($id)
    {
            try {
                $category = Category::find($id);
                if ($category) {
                    $category->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Xóa danh mục thành công',
                    ]);
                } 
            } catch (QueryException $e) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Không thể xóa loại sản phẩm có sản phẩm liên quan',
                ]);
            }
    }
    
    // public function destroy($id)
    // {
    //     $category = Category::find($id);
    
    //     if ($category) {
    //         if ($category->product()->exists()) {
    //             return response()->json([
    //                 'status' => 400,
    //                 'message' => 'Không thể xóa loại sản phẩm có sản phẩm liên quan',
    //             ]);
    //         }
    
    //         $category->delete();
    
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Xóa danh mục thành công',
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Xóa không thành công không tìm thấy id',
    //         ]);
    //     }
    // }
    // public function destroy($id)
    //     {
    //         try {
    //             $category = Category::findOrFail($id);

    //             if ($category->product()->exists()) {
    //                 return response()->json([
    //                     'status' => 400,
    //                     'message' => 'Không thể xóa loại sản phẩm có sản phẩm liên quan',
    //                 ]);
    //             }

    //             $category->delete();

    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Xóa danh mục thành công',
    //             ]);
    //         } catch (QueryException $e) {
    //             return response()->json([
    //                 'status' => 500,
    //                 'message' => 'Đã xảy ra lỗi khi xóa danh mục',
    //             ]);
    //         }
    //     }
    public function store(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'description' => 'required|max:191',
            'slug' => 'required|max:191|unique:categories',
            'name' => 'required|max:191|unique:categories',
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $category = new Category();
           
            $category->slug = $r->input('slug');
            $category->name = $r->input('name');
            $category->description = $r->input('description');
         
            $category->status = $r->input('status') === '1' ? '1' : '0';
           
            $category->save();
            return response()->json([
                'status' => 200,
                'message' => 'Thêm loại laptop thành công',
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
          
            'slug' => 'required|max:191',
            'name' => 'required|max:191|unique:categories',
            'description' => 'required|max:191',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ]);
        } else {
            $category = Category::find($id);
            if ($category) {
                $category->slug = $request->input('slug');
                $category->name = $request->input('name');
                $category->description = $request->input('description');
                $category->status = $request->input('status') === '1' ? '1' : '0';
                $category->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Sửa loại laptop thành công',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Sửa thất bại',
                ]);
            }
        }
    }
}
