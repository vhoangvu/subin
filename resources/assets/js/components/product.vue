<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-cutlery"></span>
	                		 Menu
                		</strong>
            		</div>
	
	                <div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(productGroupName, productGroupCode) in productGroupNames" :header="productGroupName" :key="productGroupCode">
									<div class="row product-line">
										<div class="col-md-4 col-sm-4 col-xs-5">
				                    		<strong>Name</strong>
				                    	</div>
				                    	<div class="col-md-3 col-sm-3 col-xs-2 no-padding-side">
				                    		<strong>Cost</strong>
				                    	</div>
				                    	<div class="col-md-3 col-sm-3 col-xs-2">
				                    		<strong>Price</strong>
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-3 no-padding-side">
				                    		<strong>Action</strong>
				                    	</div>
				                    </div>
									<div class="row product-line">
										<div class="col-md-4 col-sm-4 col-xs-5">
				                    		<input type="text" v-model="newProduct.name" placeholder="Name" class="form-control">
				                    	</div>
				                    	<div class="col-md-3 col-sm-3 col-xs-2 no-padding-side">
				                    		<bill-masked-input
										        	v-model.number="newProduct.produce_price"
										          	mask-type="currency" 
										          	class="form-control"
										          	placeholder="Cost"
										     ></bill-masked-input>
				                    	</div>
				                    	<div class="col-md-3 col-sm-3 col-xs-2">
				                    		<bill-masked-input
										        	v-model.number="newProduct.sale_price"
										          	mask-type="currency" 
										          	class="form-control"
										          	placeholder="Price"
										     ></bill-masked-input>
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-3 no-padding-side">
				                    		<button class="btn btn-primary" v-on:click="create(productGroupCode)">New</button>
				                    	</div>
				                    </div>
									<div class="row product-line" v-for="(product, index) in products[productGroupCode]" :key="product.id">
				                    	<div class="col-md-4 col-sm-4 col-xs-5">
				                    		<input type="text" v-model="product.name" placeholder="Tên Món" class="form-control">
				                    	</div>
				                    	<div class="col-md-3 col-sm-3 col-xs-2 no-padding-side">
				                    		<bill-masked-input
										        	v-model.number="product.produce_price"
										          	mask-type="currency" 
										          	class="form-control"
										          	placeholder="Cost"
										     ></bill-masked-input>
				                    	</div>
				                    	<div class="col-md-3 col-sm-3 col-xs-2">
				                    		<bill-masked-input
										        	v-model.number="product.sale_price"
										          	mask-type="currency" 
										          	class="form-control"
										          	placeholder="Price"
										     ></bill-masked-input>
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-3 no-padding-side">
				                    		<button class="btn btn-primary" v-on:click="update(product)">Save</button>
				                    		<button class="btn btn-danger" v-on:click="deleteProduct(product, productGroupCode, index)">Delete</button>
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
				products: [],
				
				productGroupNames,
				
				newProduct: {
					produce_price: 0,
					sale_price: 0,
				},
			}
		},
		created() {
			var self = this;
			
			axios.get('/product')
				.then(function(response){
					self.products = response.data;
				});
		},
		methods: {
			update(product) {
				var self = this;
				
				axios.put('/product/' + product.id, {product: product})
					.then(function(response){
						self.$snotify.success('Updated', product.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			create(productGroupCode) {
				var self = this;
				
				this.newProduct.product_group_code = productGroupCode;
				axios.post('/product', {product: this.newProduct})
					.then(function(response){
						if (self.products[response.data.product_group_code]) {
							self.products[response.data.product_group_code].unshift(response.data);
						} else {
							self.products[response.data.product_group_code] = [];
							self.products[response.data.product_group_code].unshift(response.data);
						}
						
						self.newProduct = {
							produce_price: 0,
							sale_price: 0,
						};
						
						self.$snotify.success('Added', response.data.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			deleteProduct(product, productGroupCode, dataIndex) {
				var self = this;
				
				axios.delete('/product/' + product.id)
					.then(function(response){
						self.products[productGroupCode].splice(dataIndex, 1);
						
						self.$snotify.warning('Deleted', product.name, {
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