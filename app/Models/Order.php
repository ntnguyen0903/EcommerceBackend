<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    use HasFactory;
    protected $table = "orders";
    protected $fillable =[
        'user_id',
        'firstname',
        'lastname',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zipcode',
        'payment_id',
        'payment_mode',
        'tracking_no',
        'status',
        'remark',
        'totalPrice'
    ];

    public function orderitems()
    {
        return $this->hasMany(OrderItems::class,'order_id','id');
    }

    
    protected $with = ['user'];
    public  function user()
    {
        return  $this->belongsTo(User::class, 'user_id', 'id');
    }
}
