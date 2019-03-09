<?php

namespace App\Http\Controllers;

use App\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    //
    public function __construct() {
    	$this->middleware('auth');
    }
    
    public function listt() {
    	$materials = Material::orderBy('material_group_code')
    						->orderBy('name')
    						->get()
    						->groupBy('material_group_code');
    	
    	return new JsonResponse($materials, 200);
    }
    
    public function update($materialId, Request $request) {
    	$materialData = $request->get('material', []);
    	
    	$material = Material::findOrFail($materialId);
    	$material->fill($materialData);
    	$material->save();
    	
    	return new JsonResponse($material, 200);
    }
    
    public function create(Request $request) {
    	$materialData = $request->get('material', []);
    	 
    	$material = Material::create($materialData);
    	 
    	return new JsonResponse($material, 200);
    }
    
    public function delete($materialId) {
    	Material::destroy($materialId);
    	
    	return new JsonResponse(['success' => true], 200);
    }
}
