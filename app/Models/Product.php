<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    use HasFactory;

    public function additionals(){
        return $this->hasmany('App\Models\Additional');
    }

    public function orders(){
        return $this->belongsTo('App\Models\Order');
    }

    // public function getUserProducts($order_id)
    // {
    //     return DB::table('products')
    //     ->where('products.order_id', $order_id)
    //     ->orderByRaw('products.created_at')
        
    //     ->select(
    //     /*product*/
    //     'products.id as product_id','products.quantity as product_quantity', 
    //     'products.name as product_name', 'products.price as product_price',
    //     'products.size as product_size', 'products.observation as product_observation',
    //     )
    //     ->get();
    // }
}
