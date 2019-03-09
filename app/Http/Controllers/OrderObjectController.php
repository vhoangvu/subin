<?php

namespace App\Http\Controllers;

use App\OrderObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderObjectController extends Controller
{
    //
    public function __construct() {
    	$this->middleware('auth');
    }
    
    public function listt() {
    	$orderObjects = OrderObject::orderBy('order_object_group_code')
    						->orderBy('name')
    						->get()
    						->groupBy('order_object_group_code');
    	
    	return new JsonResponse($orderObjects, 200);
    }
    
    public function update($orderObjectId, Request $request) {
    	$orderObjectData = $request->get('order_object', []);
    	
    	$orderObject = OrderObject::findOrFail($orderObjectId);
    	$orderObject->fill($orderObjectData);
    	$orderObject->save();
    	
    	return new JsonResponse($orderObject, 200);
    }
    
    public function create(Request $request) {
    	$orderObjectData = $request->get('order_object', []);
    	 
    	$orderObject = OrderObject::create($orderObjectData);
    	 
    	return new JsonResponse($orderObject, 200);
    }
    
    public function delete($orderObjectId) {
    	OrderObject::destroy($orderObjectId);
    	
    	return new JsonResponse(['success' => true], 200);
    }
}
