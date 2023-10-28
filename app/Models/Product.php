<?php

namespace App\Models;

use  App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'os',
        'ram',
    
        'slug',
        'name',
        'description',
        'brand',
        'selling_price',
        'original_price',
        'qty',
        'image',
 
        'status',
    ];


    protected $with = ['category'];
    public  function category()
    {
        return  $this->belongsTo(Category::class, 'category_id', 'id');
    }
    // public function products()
    // {
    //     return $this->hasOneThrough(
    //         Product::class,
    //         OrderItems::class,
    //         'order_id', // Khóa ngoại trong mô hình OrderItems
    //         'id', // Khóa chính trong mô hình Product
    //         'id', // Khóa chính trong mô hình Order
    //         'product_id' // Khóa ngoại trong mô hình Product
    //     );
    // }

}
