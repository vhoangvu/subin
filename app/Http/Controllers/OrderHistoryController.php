<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\OrderHistory;
use Illuminate\Support\Facades\Auth;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Order;

class OrderHistoryController extends Controller
{
    //
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function showGroupedList($orderId) {
		$histories = DB::table('order_history as oh')
						->select(DB::raw('p.name product_name, p.sale_price product_sale_price, oh.product_id, sum(oh.quantity) quantity_sum, sum(oh.quantity*oh.current_sale_price) price_sum'))	
						->leftJoin('product as p', 'p.id', '=', 'oh.product_id')
						->where('oh.order_id', $orderId)
						->groupBy('oh.product_id')
						->groupBy('p.name')
						->groupBy('p.sale_price')
						->orderBy('quantity_sum', 'desc')
						->get()
						;
		
		$histories = $histories->filter(function($item, $key){
			return $item->quantity_sum > 0;
		});				
		$price_sum_sum = $histories->sum('price_sum');
		
		return new JsonResponse([
				'data' => $histories,
				'price_sum_sum' => $price_sum_sum
		], 200);
	}
	
	public function order($productId, $orderId, Request $request) {
		$user = Auth::user();
		
		$order = Order::findOrFail($orderId);
		if ($order->status == 'PAID') {
			return new JsonResponse([
					'success' => false,
					'message' => 'This order was paid'
			], 500);
		}
		
		$product = Product::findOrFail($productId);
		 
		$quantity = $request->get('quantity', 1);
		if ($quantity == null || $quantity == '') {
			$quantity = 1;
		}
		 
		OrderHistory::create([
				'user_id' => $user->getAuthIdentifier(),
				'order_id' => $orderId,
				'product_id' => $productId,
				'quantity' => $quantity,
				'current_produce_price' => $product->produce_price,
				'current_sale_price' => $product->sale_price,
				'date' => Carbon::today()
		]);
		
		$returnGroupedHistories = $request->get('return_grouped_histories', false);
		if ($returnGroupedHistories) {
			$histories = DB::table('order_history as oh')
							->select(DB::raw('p.name product_name, p.sale_price product_sale_price, oh.product_id, sum(oh.quantity) quantity_sum, sum(oh.quantity*oh.current_sale_price) price_sum'))
							->leftJoin('product as p', 'p.id', '=', 'oh.product_id')
							->where('oh.order_id', $orderId)
							->groupBy('oh.product_id')
							->groupBy('p.name')
							->groupBy('p.sale_price')
							->orderBy('quantity_sum', 'desc')
							->get()
							;
			
			$histories = $histories->filter(function($item, $key){
				return $item->quantity_sum > 0;
			});
			$price_sum_sum = $histories->sum('price_sum');
		
			return new JsonResponse([
					'data' => $histories,
					'price_sum_sum' => $price_sum_sum
			], 200);
		}
		 
		return new JsonResponse(['success' => true], 200);
	}
}
