<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-shopping-cart"></span>
	                		 Sale
	                	</strong>
	                </div>
	
					<div class="panel-heading">
						<a href="/order" class="btn btn-warning btn-block">
							<span class="glyphicon glyphicon-shopping-cart"></span>
							 Order
						</a>
					</div>
					<div v-if="!order" class="panel-heading">
						<a href="javascript:void(0)" class="btn btn-success btn-block" v-on:click="quickOrder()">
							<span class="glyphicon glyphicon-shopping-cart"></span>
							 Quick Order
						</a>
					</div>
					<div v-if="order" class="panel-heading">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<vue-accordion :one-at-atime="true" type="success">
									<vue-panel type="success" :key="'quick_order'">
										<div slot="header">
											<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12">
													<strong>{{ 'Bill ' + order.id + ': ' }}</strong>
													<strong class="pull-right">{{ moneyFormat(groupedHistories.price_sum_sum) }}</strong>
												</div>
											</div>
										</div>
										<div class="row product-line">
					                    	<div class="col-md-1 col-sm-1 col-xs-1 product-name no-padding-side">
					                    		<strong>#</strong>
					                    	</div>
					                    	<div class="col-md-5 col-sm-3 col-xs-4 product-name no-padding-side">
					                    		<strong>Name</strong>
					                    	</div>
					                    	<div class="col-md-3 col-sm-3 col-xs-2 product-name no-padding-side">
					                    		<strong>Price</strong>
					                    	</div>
					                    	<div class="col-md-1 col-sm-1 col-xs-3 product-name no-padding-side">
					                    		<strong>Quantity</strong>
					                    	</div>
					                    	<div class="col-md-2 col-sm-3 col-xs-2 product-name no-padding-side">
					                    		<strong>Total</strong>
					                    	</div>
						           		</div>
						           		
						           		<div class="row product-line" v-for="(groupedHistory, index) in groupedHistories.data" :key="groupedHistory.product_id">
					                    	<div class="col-md-1 col-sm-1 col-xs-1 product-name no-padding-side">
					                    		{{ index + 1 }}
					                    	</div>
					                    	<div class="col-md-5 col-sm-3 col-xs-4 product-name no-padding-side">
					                    		{{ groupedHistory.product_name }}
					                    	</div>
					                    	<div class="col-md-3 col-sm-3 col-xs-2 product-name no-padding-side">
					                    		{{ moneyFormat(groupedHistory.product_sale_price) }}
					                    	</div>
					                    	<div class="col-md-1 col-sm-1 col-xs-3 product-name no-padding-side">
					                    		{{ groupedHistory.quantity_sum }}
					                    	</div>
					                    	<div class="col-md-2 col-sm-3 col-xs-2 product-name no-padding-side">
					                    		{{ moneyFormat(groupedHistory.price_sum) }}
					                    	</div>
						           		</div>
						           		
						           		<div class="row product-line summary">
						            		<div class="col-md-10 col-sm-8 col-xs-10 product-name">
					                    		<strong class="pull-right">Total</strong>
					                    	</div>
						            		<div class="col-md-2 col-sm-3 col-xs-2 no-padding-side product-name">
					                    		<strong>{{ moneyFormat(groupedHistories.price_sum_sum) }}</strong>
					                    	</div>
						            	</div>
						            	<div class="row product-line summary">
						            		<div class="col-md-12 col-sm-12 col-xs-12">
								            	<button v-if="order.status != 'PAID'" class="btn btn-sm btn-primary" v-on:click="pay()">Paid</button>
						                		<button v-if="order.status != 'PAID'" v-on:click="deleteOrder()" class="btn btn-sm btn-danger">Delete</button>
											</div>
										</div>
									</vue-panel>	
								</vue-accordion>
							</div>
						</div>
					</div>
	                <div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(productGroup, productGroupCode) in products" :header="productGroupNames[productGroupCode]" :key="productGroupCode">
									
								
				                    <div class="row product-line" v-for="product in productGroup" :key="product.id">
				                    	
				                    	
				                    	
				                    	<div class="col-md-8 col-sm-5 col-xs-5 product-name">
				                    		{{ product.name }}
				                    	</div>
				                    	<div class="col-md-1 col-sm-2 col-xs-2 no-padding-side">
				                    		<button class="btn btn-primary center-block" v-on:click="saveOrOrderProduct(product)">
				                    			<span class="glyphicon glyphicon-plus-sign"></span>
				                    		</button>
				                    	</div>
				                    	<div class="col-md-2 col-sm-3 col-xs-3 no-padding-side">
				                    		<select class="form-control" v-model="product.quantity">
				                    			<option value="0">0</option>
				                    			<option value="1">1</option>
				                    			<option value="2">2</option>
				                    			<option value="3">3</option>
				                    			<option value="4">4</option>
				                    			<option value="5">5</option>
				                    			<option value="6">6</option>
				                    			<option value="7">7</option>
				                    			<option value="8">8</option>
				                    			<option value="9">9</option>
				                    			<option value="10">10</option>
				                    		</select>
				                    	</div>
				                    	<div class="col-md-1 col-sm-2 col-xs-2 no-padding-side">
				                    		<button class="btn btn-danger center-block" v-on:click="unsaleOrUnOrderProduct(product)">
				                    			<span class="glyphicon glyphicon-minus-sign"></span>
				                    		</button>
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
				order: null,
				groupedHistories: [],
				products: [],
				
				productGroupNames,
				
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
			moneyFormat,
			saveOrOrderProduct(product) {
				if (this.order) {
					this.orderProduct(product);
				} else {
					this.sale(product);
				}
			},
			unsaleOrUnOrderProduct(product) {
				if (this.order) {
					this.unOrderProduct(product);
				} else {
					this.unsale(product);
				}
			},
			sale(product) {
				var self = this;
				var quantity = product.quantity;
				if (!quantity) {
					quantity = 1;
				}
				
				axios.post('/salehistory/' + product.id, {quantity: quantity})
					.then(function(response){
						product.quantity = 0;
						
						self.$snotify.success('Added', quantity + ' ' + product.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			unsale(product) {
				var self = this;
				var quantity = product.quantity;
				if (!quantity) {
					quantity = 1;
				}
				
				axios.post('/salehistory/' + product.id, {quantity: -quantity})
					.then(function(response){
						product.quantity = 0;
						
						self.$snotify.warning('Deleted', quantity + ' ' + product.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			orderProduct(product) {
				var self = this;
				var quantity = product.quantity;
				if (!quantity) {
					quantity = 1;
				}
				
				axios.post('/orderhistory/' + product.id + '/' + this.order.id, {quantity: quantity, return_grouped_histories: true})
					.then(function(response){
						self.groupedHistories = response.data;
						product.quantity = 0;
						
						self.$snotify.success('Added', quantity + ' ' + product.name, {
						  timeout: notifyTimeout,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			unOrderProduct(product) {
				var self = this;
				var quantity = product.quantity;
				if (!quantity) {
					quantity = 1;
				}
				
				axios.post('/orderhistory/' + product.id + '/' + this.order.id, {quantity: -quantity, return_grouped_histories: true})
					.then(function(response){
						self.groupedHistories = response.data;
						product.quantity = 0;
						
						self.$snotify.warning('Deleted', quantity + ' ' + product.name, {
						  timeout: notifyTimeout,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			quickOrder() {
				var self = this;
			
				axios.post('/order/quick')
					.then(function(response){
						self.order = response.data;
						
						self.$snotify.success('Created Order', self.order.id, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			pay() {
				var self = this;
				
				axios.post('/order/' + this.order.id + '/pay')
					.then(function(response){
						self.order = null;
						
						self.$snotify.success('Paid', 'Thank You', {
						  timeout: notifyTimeout,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			deleteOrder() {
				var self = this;
				
				axios.delete('/order/' + this.order.id)
					.then(function(response){
						self.order = null;
						self.groupedHistories = [];
					
						self.$snotify.warning('Deleted Order', self.orderId, {
						  timeout: notifyTimeout,
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