<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\SaleHistory;
use App\OrderObject;

class OrderController extends Controller
{
    //
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function show($orderId) {
		$order = Order::with('order_object')->findOrFail($orderId);
		
		return new JsonResponse($order, 200);
	}
	
	public function listt() {
		$orders = Order::with(['order_object', 'order_histories'])
						->orderBy('status')
						->orderBy('order_object_id')
						->where('status', '<>', 'PAID')
						->get()
						->groupBy(function($item, $key) {
							return $item->order_object->order_object_group_code;
						});
							
		$price_sum = [];
		foreach ($orders as $orderObjectGroupCode => $orderList) {
			foreach ($orderList as $index => $order) {
				$price_sum[$order->id] = $order->order_histories->sum(function($order_history){
					return $order_history->current_sale_price * $order_history->quantity;
				});
			}
		}
		
		return new JsonResponse([
				'data' => $orders,
				'price_sum' => $price_sum
		], 200);
	}
	
	public function create(Request $request) {
		$user = Auth::user();
		$orderObject = $request->get('order_object', []);
		
		$order = Order::create([
				'user_id' => $user->getAuthIdentifier(),
				'order_object_id' => $orderObject['id'],
				'date' => Carbon::today(),
				'status' => 'CREATED',
		]);
		
		return new JsonResponse($order, 200);
	}
	
	public function createQuick() {
		$user = Auth::user();
		$orderObject = OrderObject::where('order_object_group_code', 'CUSTOM')
									->where('name', 'Quick Order')
									->get()->first();
		
		if (!$orderObject) {
			$orderObject = OrderObject::create([
					'order_object_group_code' => 'CUSTOM',
					'name' => 'Quick Order'
			]);
		}
		
		$order = Order::create([
				'user_id' => $user->getAuthIdentifier(),
				'order_object_id' => $orderObject['id'],
				'date' => Carbon::today(),
				'status' => 'CREATED',
		]);
		
		return new JsonResponse($order, 200);
	}
	
	public function initOrderHistory($orderId) {
		return view('orderhistoryinit', [
				'orderId' => $orderId
		]);
	}
	
	public function reviewOrderHistory($orderId) {
		return view('orderhistoryreview', [
				'orderId' => $orderId
		]);
	}
	
	public function bill($orderId) {
		return view('orderbill', [
				'orderId' => $orderId
		]);
	}
	
	public function pay($orderId) {
		$order = Order::findOrFail($orderId);
		
		if ($order->status == 'PAID') {
			return new JsonResponse([
					'success' => false,
					'message' => 'This order was paid',
			], 500);
		}
		
		DB::beginTransaction();
		try {
			foreach ($order->order_histories as $orderHistory) {
				SaleHistory::create($orderHistory->toArray());
			}
			$order->status = 'PAID';
			$order->save();
			DB::commit();
		} catch(\Exception $e) {
			DB::rollback();
		}
		
		return new JsonResponse($order->load('order_object'), 200);
	}
	
	public function delete($orderId) {
		$order = Order::findOrFail($orderId);
		
		if ($order->status == 'PAID') {
			return new JsonResponse([
					'success' => false,
					'message' => 'This order was paid',
			], 500);
		}
		
		Order::destroy($orderId);
		
		return new JsonResponse([
				'success' => true
		], 200);
	}
}
