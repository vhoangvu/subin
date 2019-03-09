<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\SaleHistory;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class SaleHistoryController extends Controller
{
    //
    public function __construct() {
    	$this->middleware('auth');
    }
    
    public function sale($productId, Request $request) {
    	$user = Auth::user();
    	$product = Product::findOrFail($productId);
    	
    	$quantity = $request->get('quantity', 1);
    	if ($quantity == null || $quantity == '') {
    		$quantity = 1;
    	}
    	
    	SaleHistory::create([
    			'user_id' => $user->getAuthIdentifier(),
    			'product_id' => $productId,
    			'quantity' => $quantity,
    			'current_produce_price' => $product->produce_price,
    			'current_sale_price' => $product->sale_price,
    			'date' => Carbon::now()
    	]);
    	
    	return new JsonResponse(['success' => true], 200);
    }
    
    public function report(Request $request) {
    	$from = $request->get('from', Carbon::today());
    	$to = $request->get('to', Carbon::today());
    	$exportExcel = $request->get('export_excel', false);
    	if ($exportExcel == 'true') {
    		$exportExcel = true;
    	} else {
    		$exportExcel = false;
    	}
    	
    	$data = DB::table('product as p')
    				->select(DB::raw('	sh.date,
    									p.id, 
    									p.product_group_code, 
    									sh.current_sale_price,
    									sh.current_produce_price,
    									p.name, sum(sh.quantity) sale_quantity, 
    									sum(sh.quantity) * sh.current_sale_price sale_amount,  
    									(sum(sh.quantity) * sh.current_sale_price - sum(sh.quantity) * sh.current_produce_price) profit_amount'))
    			    ->leftJoin('sale_history as sh', 'p.id', '=', 'sh.product_id')
    			    ->where('sh.date', '>=', $from)
    			    ->where('sh.date', '<=', $to)
    			    ->groupBy('sh.date')
    				->groupBy('p.id')
    				->groupBy('p.product_group_code')
    				->groupBy('p.name')
    				->groupBy('sh.current_sale_price')
    				->groupBy('sh.current_produce_price')
    				->orderBy('sale_amount', 'desc')
    				->orderBy('p.product_group_code')
    				->orderBy('p.name')
    				->get();
    	
    	$data = $data->groupBy('date');
    	$sale_amount_sum = null;
    	$profit_amount_sum = null;
    	$sale_amount_sum_sum = 0;
    	$profit_amount_sum_sum = 0;
    	foreach ($data as $date => $saleData) {
    		$sale_amount_sum[$date] = $saleData->sum('sale_amount');
    		$sale_amount_sum_sum += $sale_amount_sum[$date];
    		$profit_amount_sum[$date] = $saleData->sum('profit_amount');
    		$profit_amount_sum_sum += $profit_amount_sum[$date];
    	}
    	
    	if ($exportExcel) {
    		Excel::create('salereport', function($excel) use($data, $sale_amount_sum, $profit_amount_sum, $sale_amount_sum_sum, $profit_amount_sum_sum, $from, $to){
    				
    			$excel->sheet('sheet', function($sheet) use($data, $sale_amount_sum, $profit_amount_sum, $sale_amount_sum_sum, $profit_amount_sum_sum, $from, $to) {
    				$rows = [];
    				$header = [];
    				$header[] = '#';
    				$header[] = 'Name';
    				$header[] = 'Cost';
    				$header[] = 'Price';
    				$header[] = 'Quantity';
    				$header[] = 'Total';
    				$header[] = 'Profit';
    	
    				$rows[] = $header;
    				
    				foreach ($data as $date => $dateData) {
    					$row = ['Date: ' . $this->frontendDateFormat($date), '', '', '', 'Total Sale & Profit', number_format($sale_amount_sum[$date]), number_format($profit_amount_sum[$date])];
    					$rows[] = $row;
    					
    					foreach ($dateData as $index => $item) {
    						$row = [];
    						 
    						$row[] = $index + 1;
    						$row[] = $item->name;
    						$row[] = number_format($item->current_produce_price);
    						$row[] = number_format($item->current_sale_price);
    						$row[] = $item->sale_quantity;
    						$row[] = number_format($item->sale_amount);
    						$row[] = number_format($item->profit_amount);
    						 
    						$rows[] = $row;
    					
    					}
    				}
    				
    				$row = ['', '', '', '', 'Total Sale & Profit From ' . $this->frontendDateFormat($from) . 'To ' . $this->frontendDateFormat($to) , number_format($sale_amount_sum_sum), number_format($profit_amount_sum_sum)];
    				$rows[] = $row;
    	
    				$sheet->fromArray($rows, null, 'A1', false, false);
    			});
    	
    		})->export();
    	}
    	
    	return new JsonResponse([
    			'data' => $data,
    			'sale_amount_sum' => $sale_amount_sum,
    			'profit_amount_sum' => $profit_amount_sum,
    			'sale_amount_sum_sum' => $sale_amount_sum_sum,
    			'profit_amount_sum_sum' => $profit_amount_sum_sum,
    	], 200);
    }
    
    private function frontendDateFormat($dateStringFromDB) {
    	if ($dateStringFromDB) {
    		return Carbon::createFromFormat('Y-m-d', $dateStringFromDB)->format('d/m/Y');
    	}
    	return '';
    }
}
