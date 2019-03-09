<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Product;

class ProductController extends Controller
{
    //
    public function __construct() {
    	$this->middleware('auth');
    }
    
    public function listt() {
    	$products = Product::orderBy('product_group_code')
    						->orderBy('name')
    						->get()
    						->groupBy('product_group_code');
    	
    	return new JsonResponse($products, 200);
    }
    
    public function update($productId, Request $request) {
    	$productData = $request->get('product', []);
    	
    	$product = Product::findOrFail($productId);
    	$product->fill($productData);
    	$product->save();
    	
    	return new JsonResponse($product, 200);
    }
    
    public function create(Request $request) {
    	$productData = $request->get('product', []);
    	 
    	$product = Product::create($productData);
    	 
    	return new JsonResponse($product, 200);
    }
    
    public function delete($productId) {
    	Product::destroy($productId);
    	
    	return new JsonResponse(['success' => true], 200);
    }
}
