<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Additional;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Events\OrderNotification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::getTodayOrders();
        return response()->json($orders, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrdersByDate($startDate = 'empty', $endDate = 'empty')
    {
        if($startDate == 'empty' || $endDate == 'empty'){
            $orders = Order::getTodayOrders();
            return response()->json($orders, 200);
            // return response()->json($orders->load('users')->load('products')->load('products.additionals'), 200);
        }else{
            $orders = Order::getOrdersByDate($startDate, $endDate);
            return response()->json($orders, 200);
        }
        return 'Date Not found';
    }

    public function getTotalSalesInAday($startDate = 'empty', $endDate = 'empty'){
        if($startDate == 'empty' || $endDate == 'empty'){
            $totalSales = Order::getTotalSales();
            return response()->json($totalSales, 200);
        }else{
            $totalSales = Order::getTotalSalesByDate($startDate, $endDate);
            return response()->json($totalSales, 200);
        }
        return 'Date Not found';
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function getTotalOrderPrice($id)
    // {
    //     $TotalOrderPrice = Order::getTotalOrderPrice($id);
    //     return response()->json($TotalOrderPrice, 200);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log::info($request);
        try {
            $order = new Order();
            $order->wayToPay = $request->get('wayToPay');
            $order->change = $request->get('change');
            $order->address = $request->get('address');
            $order->reference =  $request->get('reference');
            $order->price =  $request->get('price');
            $order->status =  'null';
            $order->save();

            $requestUser = $request->get('user');
            $user = new User();
            $user->order_id = $order->id;
            $user->name = $requestUser['name'];
            $user->phone_number = $requestUser['phone_number'];
            $user->save();

            $requestProducts = $request->get('products');
            for ($i=0; $i < count($requestProducts); $i++) { 
                $product = new Product();
                $product->order_id = $order->id;
                $product->quantity = $requestProducts[$i]['quantity'];
                $product->name = $requestProducts[$i]['name'];
                $product->price = $requestProducts[$i]['price'];
                $product->size = $requestProducts[$i]['size'];
                $product->observation = $requestProducts[$i]['observation'];

                $product->save();

                $additionalsProduct = $requestProducts[$i]['additionals'];
                // return response()->json($additionalsProduct[0]['name']);
                for ($j=0; $j < count($additionalsProduct); $j++) {
                    $additional = new Additional();
                    $additional->product_id = $product->id;
                    $additional->name = $additionalsProduct[$j]['name'];
                    $additional->type = $additionalsProduct[$j]['type'];
                    $additional->save();
                }
            }

            broadcast(New OrderNotification( Order::getOrderById($order->id) ) );

            return response()->json("Order created id:".$order->id, 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json($product->save());
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($orderId)
    {
        $newOrder =  Order::getOrderById($orderId);
        return $newOrder;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->get('status');
        $order->update();
        
        return response()->json("Order updated id:".$order->id, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        
        return response()->json("Order deleted id:".$order->id, 200);
    }
}
