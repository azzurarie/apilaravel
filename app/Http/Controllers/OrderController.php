<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Validator;

class OrderController extends Controller
{
    public function sendRequestOrder(Request $request){
        $validator = Validator::make($request->all(),[
            'customer_id' => 'required',
            'driver_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $orders = DB::table('orders')
        ->where('customer_id', $request->customer_id)
        ->where('driver_id', $request->driver_id)
        ->first();


        
        if(!empty($orders)){
            if($orders)
            return response()->json($orders->id);       
            
        }

        $order = Order::create(
            [
                'customer_id'=>$request->customer_id,
                'driver_id'=>$request->driver_id,
                'order'=>false,
                'request'=>true,
            ]);

            return response()->json($order);

    }

    public function acceptOrder(Request $request){
        $validator = Validator::make($request->all(),[
            'customer_id' => 'required',
            'driver_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());       
        }

        $orders = DB::table('orders')
        ->where('customer_id', $request->customer_id)
        ->where('driver_id', $request->driver_id)
        ->first();

        if(!empty($orders)){
            $orders              = Order::find($orders->id);
            $orders->customer_id  = $request->customer_id;
            $orders->driver_id    = $request->driver_id;
            $orders->order        = true;
            $orders->request       = true;                 
            // return response()->json($orders);
            if($orders->save()){
                return response()->json( 'Permintaan disetujui');
            }else{
                return response()->json('Permintaan gagal disetujui');
            }
        }

        return response()->json('unknown accept');
    }
}
