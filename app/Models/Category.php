<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps=false;//set time to false
    protected $fillable=['status','name','slug','description'];//'meta_title','meta_keywords','meta_description',
    protected $primaryKey='id';
    protected $table='categories';
    public function product(){
        return $this->hasMany(Product::class,'id','id');
    }
}
