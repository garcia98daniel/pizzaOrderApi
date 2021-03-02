<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Order extends Model
{
    use HasFactory;
    protected $table = 'Order';

    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['wayToPay', 'change', 'address', 'reference','price','type'];

    public function products(){
        return $this->hasmany('App\Models\Product');
    }

    public function user(){
        return $this->hasOne('App\Models\User');
    }

    public static function getTodayOrders()
    {
        // $dt = Carbon::now('America/Bogota');
        // dd($dt->copy()->startOfDay(), $dt->copy()->endOfDay());
        $startDay = Carbon::now('America/Bogota')->startOfDay();
        // dd($startDay);
        $endDay   = $startDay->copy()->endOfDay();

        return $orders = Order::with(['user','products','products.additionals'])
        ->orderBy('orders.created_at')
        ->where('orders.status','=','acepted')
        ->whereBetween('orders.created_at', [$startDay , $endDay])
        ->orWhere('orders.status','=','null')
        ->whereBetween('orders.created_at', [$startDay , $endDay])
        // ->where('orders.created_at','=', Carbon::now('America/Bogota')->format('Y-m-d'))
        ->get();
    }

    public static function getOrdersByDate($startDate, $endDate)
    {
        // $dt = Carbon::now('America/Bogota');
        $startDay = Carbon::parse($startDate)->startOfDay();
        $endDay   = Carbon::parse($endDate)->endOfDay();
        // dd($startDay, $endDay);

        return $orders = Order::with(['user','products','products.additionals'])
        ->orderBy('orders.created_at')
        ->whereBetween('orders.created_at', [$startDay , $endDay])
        // ->where('orders.created_at','=', )
        ->where('orders.status','=','acepted')
        ->with(['products.additionals'])
        ->get();
    }

    public static function getTotalSales()
    {
        // $dt = Carbon::now('America/Bogota');
        // dd($dt->copy()->startOfDay(), $dt->copy()->endOfDay());
        $startDay = Carbon::now('America/Bogota')->startOfDay();
        $endDay   = $startDay->copy()->endOfDay();
        // return Order::where('orders.created_at','=', $date)
        return Order::whereBetween('orders.created_at', [$startDay , $endDay])
        ->where('orders.status','=','acepted')
        ->sum('orders.price');
    }
//usar whereBetween()->endOfday
    public static function getTotalSalesByDate($startDate, $endDate)
    {
        // $dt = Carbon::now('America/Bogota');
        // dd($dt->copy()->startOfDay(), $dt->copy()->endOfDay());
        $startDay = Carbon::parse($startDate)->startOfDay();
        $endDay   = Carbon::parse($endDate)->endOfDay();
        // return Order::where('orders.created_at','=', $date)
        return Order::whereBetween('orders.created_at', [$startDay , $endDay])
        ->where('orders.status','=','acepted')
        ->sum('orders.price');
    }

    public static function getOrderById($orderId)
    {   
        $order = Order::with(['user','products','products.additionals'])
        ->where('orders.id','=', $orderId)->get();
        return response()->json($order);
    }
}

