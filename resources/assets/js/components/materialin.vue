<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-import"></span>
	                		 Add Inventory
	                	</strong>
	                </div>
	
	                <div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(materialGroupName, materialGroupCode) in materialGroupNames" :header="materialGroupName" :key="materialGroupCode">
									<div class="row product-line">
										<div class="col-md-3 col-sm-5 col-xs-4">
				                    		<strong>Name</strong>
				                    	</div>
				                    	<div class="col-md-2 col-sm-1 col-xs-2 no-padding-side">
				                    		<strong>Unit</strong>
				                    	</div>
				                    	<div class="col-md-3 col-sm-2 col-xs-2">
				                    		<strong>Total</strong>
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-2 no-padding-side">
				                    		<strong>Price</strong>
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-2">
				                    		<strong>Action</strong>
				                    	</div>
				                    </div>
									<div class="row product-line" v-for="(material, index) in materials[materialGroupCode]" :key="material.id">
				                    	<div class="col-md-3 col-sm-5 col-xs-4 product-name">
				                    		{{ materialHistories[material.id].material_name }}
				                    	</div>
				                    	<div class="col-md-2 col-sm-1 col-xs-2 no-padding-side">
				                    		<select class="form-control" v-model="materialHistories[material.id].unit_code">
				                    			<option value="">Unit</option>
				                    			<option v-for="(unitName, unitCode) in unitNames" :value="unitCode">{{ unitName }}</option>
				                    		</select>
				                    	</div>
				                    	<div class="col-md-3 col-sm-2 col-xs-2">
				                    		<input type="text" v-model="materialHistories[material.id].quantity" placeholder="Total" class="form-control">
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-2 no-padding-side">
				                    		<bill-masked-input
										        	v-model.number="materialHistories[material.id].price"
										          	mask-type="currency" 
										          	class="form-control"
										          	placeholder="Price"
										     ></bill-masked-input>
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-2">
				                    		<button class="btn btn-primary" v-on:click="createHistory(materialHistories[material.id])">+</button>
				                    	</div>
				                    </div>
			                    </vue-panel>
		                    </vue-accordion>
		            	</form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>		
</template>

<script>
	export default {
		data() {
			return {
				materials: [],
				materialHistories: {},
				
				materialGroupNames,
				unitNames,
				
			}
		},
		created() {
			var self = this;
			
			axios.get('/material')
				.then(function(response){
					self.materials = response.data;
					
					var materialHistories = {};
					$.each(self.materials, function(materialGroupCode, materialData){
						$.each(materialData, function(index, material){
							materialHistories[material.id] = {
								material_id: material.id,
								material_name: material.name,
								unit_code: null,
								quantity: null,
								price: 0,
							};
						});
					});
					
					self.materialHistories = materialHistories;
				});
		},
		methods: {
			createHistory(materialHistory) {
				var self = this;
				
				axios.post('/materialinouthistory/in', {material_history: materialHistory})
					.then(function(response){
						self.materialHistories[materialHistory.material_id].unit_code = null;
						self.materialHistories[materialHistory.material_id].quantity = null;
						self.materialHistories[materialHistory.material_id].price = 0;
							
						self.$snotify.success('Added', materialHistory.material_name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
		}
	}
</script>