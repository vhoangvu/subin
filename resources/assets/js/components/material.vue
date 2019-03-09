<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-leaf"></span>
	                		 Inventory
                		 </strong>
            		 </div>
	
	                <div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(materialGroupName, materialGroupCode) in materialGroupNames" :header="materialGroupName" :key="materialGroupCode">
									<div class="row product-line">
										<div class="col-md-10 col-sm-10 col-xs-7">
				                    		<strong>Name</strong>
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-5 no-padding-side">
				                    		<strong>Action</strong>
				                    	</div>
				                    </div>
									<div class="row product-line">
										<div class="col-md-10 col-sm-10 col-xs-7">
				                    		<input type="text" v-model="newMaterial.name" placeholder="Name" class="form-control">
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-5 no-padding-side">
				                    		<button class="btn btn-primary" v-on:click="create(materialGroupCode)">Add</button>
				                    	</div>
				                    </div>
									<div class="row product-line" v-for="(material, index) in materials[materialGroupCode]" :key="material.id">
				                    	<div class="col-md-10 col-sm-10 col-xs-7">
				                    		<input type="text" v-model="material.name" placeholder="Name" class="form-control">
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-5 no-padding-side">
				                    		<button class="btn btn-primary" v-on:click="update(material)">Save</button>
				                    		<button class="btn btn-danger" v-on:click="deleteMaterial(material, materialGroupCode, index)">Delete</button>
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
				
				materialGroupNames,
				
				newMaterial: {},
			}
		},
		created() {
			var self = this;
			
			axios.get('/material')
				.then(function(response){
					self.materials = response.data;
				});
		},
		methods: {
			update(material) {
				var self = this;
				
				axios.put('/material/' + material.id, {material: material})
					.then(function(response){
						self.$snotify.success('Updated', material.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			create(materialGroupCode) {
				var self = this;
				
				this.newMaterial.material_group_code = materialGroupCode;
				axios.post('/material', {material: this.newMaterial})
					.then(function(response){
						if (self.materials[response.data.material_group_code]) {
							self.materials[response.data.material_group_code].unshift(response.data);
						} else {
							self.materials[response.data.material_group_code] = [];
							self.materials[response.data.material_group_code].unshift(response.data);
						}
						
						self.newMaterial = {};
						
						self.$snotify.success('Added', response.data.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			deleteMaterial(material, materialGroupCode, dataIndex) {
				var self = this;
				
				axios.delete('/material/' + material.id)
					.then(function(response){
						self.materials[materialGroupCode].splice(dataIndex, 1);
						
						self.$snotify.warning('Deleted', material.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			}
		}
	}
</script>