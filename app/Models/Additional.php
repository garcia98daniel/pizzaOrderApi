<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Additional extends Model
{
    use HasFactory;

    public function products(){
        return $this->belongsTo('App\Models\Product');
    }

    // public static function getAdditionalProduct($product_id)
    // {
    //     return DB::table('additionals')
    //     ->where('additionals.product_id', $product_id)
    //     ->orderByRaw('additionals.created_at')
        
    //     ->select(
    //     /*additionals*/
    //     'additionals.id as additional_id','additionals.product_id as additional_product_id', 'additionals.name as additional_name',
    //     'additionals.price as additional_price',)
    //     ->get();
    // }
}
