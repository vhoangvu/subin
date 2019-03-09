<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MaterialInOutHistory;
use Illuminate\Support\Facades\DB;
use App\MaterialInventory;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class MaterialInOutHistoryController extends Controller
{
    //
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function createIn(Request $request) {
		$materialHistoryData = $request->get('material_history', []);
		
		$materialInOutHistory = null;
		
		DB::beginTransaction();
		try {
			$materialHistoryData['in_out_code'] = 'IN';
			$materialHistoryData['date'] = Carbon::today();
			$materialInOutHistory = MaterialInOutHistory::create($materialHistoryData);
			
			$materialInventory = DB::table('material_inventory')->where('material_id', '=', $materialInOutHistory->material_id)
																->where('unit_code', '=', $materialInOutHistory->unit_code)
																->lockForUpdate()
																->get()
																->first();
			
			if (!$materialInventory) {
				$materialInventory = MaterialInventory::create([
						'material_id' => $materialInOutHistory->material_id,
						'unit_code' => $materialInOutHistory->unit_code,
						'quantity' => $materialInOutHistory->quantity
				]);
			} else {
				$materialInventory = MaterialInventory::findOrFail($materialInventory->id);
				$materialInventory->quantity = $materialInventory->quantity + $materialInOutHistory->quantity;
				$materialInventory->save();
			}
			
			DB::commit();
		}catch(\Exception $e) {
			DB::rollBack();
		}
	
		return new JsonResponse($materialInOutHistory, 200);
	}
	
	public function createOut(Request $request) {
		$materialHistoryData = $request->get('material_history', []);
	
		$materialInOutHistory = null;
	
		DB::beginTransaction();
		try {
			$materialHistoryData['in_out_code'] = 'OUT';
			$materialHistoryData['date'] = Carbon::today();
			$materialInOutHistory = MaterialInOutHistory::create($materialHistoryData);
				
			$materialInventory = DB::table('material_inventory')->where('material_id', '=', $materialInOutHistory->material_id)
																->where('unit_code', '=', $materialInOutHistory->unit_code)
																->lockForUpdate()
																->get()
																->first();
				
			if (!$materialInventory) {
				$materialInventory = MaterialInventory::create([
						'material_id' => $materialInOutHistory->material_id,
						'unit_code' => $materialInOutHistory->unit_code,
						'quantity' => -$materialInOutHistory->quantity
				]);
			} else {
				$materialInventory = MaterialInventory::findOrFail($materialInventory->id);
				$materialInventory->quantity = $materialInventory->quantity - $materialInOutHistory->quantity;
				$materialInventory->save();
			}
				
			DB::commit();
		}catch(\Exception $e) {
			DB::rollBack();
		}
	
		return new JsonResponse($materialInOutHistory, 200);
	}
	
	public function reportInventory(Request $request) {
		$exportExcel = $request->get('export_excel', false);
		if ($exportExcel == 'true') {
			$exportExcel = true;
		} else {
			$exportExcel = false;
		}
		 
		$data = DB::table('material as m')
					->select(DB::raw('	m.id,
										m.material_group_code,
										m.name,
										mi.unit_code,
										mi.quantity'))
    				->leftJoin('material_inventory as mi', 'm.id', '=', 'mi.material_id')
    				->orderBy('mi.quantity')
    				->get();
    									 
    	if ($exportExcel) {
    		Excel::create('inventoryreport', function($excel) use($data){

    			$excel->sheet('sheet', function($sheet) use($data) {
    				$rows = [];
    				$header = [];
    				$header[] = '#';
    				$header[] = 'Name';
    				$header[] = 'Unit';
    				$header[] = 'Total';
    												
    				$rows[] = $header;

    				foreach ($data as $index => $item) {
    					$row = [];
    															
    					$row[] = $index + 1;
    					$row[] = $item->name;
    					$row[] = $this->getUnitCodeName($item->unit_code);
    					$row[] = $item->quantity;
    														
    					$rows[] = $row;
    				}
    												
    				$sheet->fromArray($rows, null, 'A1', false, false);
    			});
    												 
    		})->export();
    	}
    									 
    	return new JsonResponse([
    		'data' => $data,
    	], 200);
	}
	
	public function reportInventorySpending(Request $request) {
		$from = $request->get('from', Carbon::today());
		$to = $request->get('to', Carbon::today());
		$exportExcel = $request->get('export_excel', false);
		if ($exportExcel == 'true') {
			$exportExcel = true;
		} else {
			$exportExcel = false;
		}
		 
		$data = DB::table('material as m')
					->select(DB::raw('	mh.date,
										m.id,
										m.material_group_code,
										m.name,
                                        sum(mh.quantity) quantity,
										sum(mh.price * mh.quantity) price'))
	    			->leftJoin('material_in_out_history as mh', 'm.id', '=', 'mh.material_id')
	    			->where('mh.date', '>=', $from)
	    			->where('mh.date', '<=', $to)
	    			->where('mh.in_out_code', '=', 'IN')
	    			->groupBy('mh.date')
	    			->groupBy('m.id')
	    			->groupBy('m.material_group_code')
	    			->groupBy('m.name')
	    			->orderBy('price', 'desc')
	    			->get();
	    									 
    	$data = $data->groupBy('date');
    	$price_sum = null;
    	$price_sum_sum = 0;
    	foreach ($data as $date => $materialData) {
    		$price_sum[$date] = $materialData->sum('price');
    		$price_sum_sum += $price_sum[$date];
    	}
	    									 
	    if ($exportExcel) {
	    	Excel::create('inventoryspendingreport', function($excel) use($data, $price_sum, $price_sum_sum, $from, $to){
	
	    		$excel->sheet('sheet', function($sheet) use($data, $price_sum, $price_sum_sum, $from, $to) {
	    			$rows = [];
	    			$header = [];
	    			$header[] = '#';
	    			$header[] = 'Name';
	    			$header[] = 'Total';
	    			$header[] = 'Cost';
	    												
	    			$rows[] = $header;
	
	    			foreach ($data as $date => $dateData) {
	    				$row = ['Date: ' . $this->frontendDateFormat($date), 'Total', number_format($price_sum[$date])];
	    				$rows[] = $row;
	    														
	    				foreach ($dateData as $index => $item) {
	    					$row = [];
	    															
	    					$row[] = $index + 1;
	    					$row[] = $item->name;
	    					$row[] = $item->quantity;
	    					$row[] = number_format($item->price);
	    														
	    					$rows[] = $row;
	    															
	    				}
	    			}
	
	    			$row = ['', 'Total From ' . $this->frontendDateFormat($from) . 'To ' . $this->frontendDateFormat($to) , number_format($price_sum_sum)];
	    			$rows[] = $row;
	    												 
	    			$sheet->fromArray($rows, null, 'A1', false, false);
	    		});
	    												 
	    	})->export();
	    }
	    									 
	    return new JsonResponse([
    								'data' => $data,
    								'price_sum' => $price_sum,
    								'price_sum_sum' => $price_sum_sum,
    	], 200);
	}
	
	private function getUnitCodeName($unitCode) {
		$unitNames = [
			'KG' => 'Kg',
			'GR' => 'Gr',
			'CAI' => 'Item',
			'CUON' => 'Roll',
			'CHAI' => 'Bottle',
			'THUNG' => 'Box',
			'BICH' => 'Bag',
			'OZ' => 'Oz',
			'CHUC' => 'Dozen',
			'ONG' => 'Tube',
			'LIT' => 'Liter',
			'GOI' => 'Package',
			'LB' => 'Pound',
			'BUN' => 'Bunch'
		];
			
		return isset($unitNames[$unitCode]) ? $unitNames[$unitCode] : null;
	}
	
	private function frontendDateFormat($dateStringFromDB) {
		if ($dateStringFromDB) {
			return Carbon::createFromFormat('Y-m-d', $dateStringFromDB)->format('d/m/Y');
		}
		return '';
	}
}
