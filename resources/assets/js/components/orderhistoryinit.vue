<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>Order</strong> for <strong>{{ order.order_object.name }}</strong>
	                	<a v-if="order.status != 'PAID'" href="javascript:void(0)" v-on:click="deleteOrder()" class="btn btn-sm btn-danger pull-right">Delete</a>
	                </div>
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-4 col-sm-4 col-xs-4">
								<a :href="'/order/' + orderId + '/orderhistory/review'" class="btn btn-sm btn-warning btn-block">Review</a>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
								<a href="/order" class="btn btn-sm btn-primary btn-block">New</a>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
								<a href="/sale" class="btn btn-sm btn-info btn-block">Continue Sale</a>
							</div>
						</div>
					</div>
					<div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(productGroup, productGroupCode) in products" :header="productGroupNames[productGroupCode]" :key="productGroupCode">
									<div class="row product-line" v-for="product in productGroup" :key="product.id">
				                    	<div class="col-md-8 col-sm-5 col-xs-5 product-name">
				                    		{{ product.name }} ({{ product.clicked }})
				                    	</div>
				                    	<div class="col-md-1 col-sm-2 col-xs-2 no-padding-side">
				                    		<button :disabled="order.status == 'PAID'" class="btn btn-primary center-block" v-on:click="orderProduct(product)">
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
				                    		<button :disabled="order.status == 'PAID'" class="btn btn-danger center-block" v-on:click="unOrderProduct(product)">
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
				orderId: document.head.querySelector('meta[name="order-id"]').content,
				order: {
					order_object: {
					
					}
				},
				products: [],
				
				productGroupNames,
				
			}
		},
		created() {
			var self = this;
			
			axios.get('/order/' + this.orderId)
				.then(function(response){
					self.order = response.data;
				});
			
			axios.get('/product')
				.then(function(response){
					self.products = response.data;
				});
		},
		methods: {
			orderProduct(product) {
				var self = this;
				var quantity = product.quantity;
				if (!quantity) {
					quantity = 1;
				}
				
				axios.post('/orderhistory/' + product.id + '/' + this.orderId, {quantity: quantity})
					.then(function(response){
						product.clicked = parseInt(product.clicked) + parseInt(quantity);
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
				
				axios.post('/orderhistory/' + product.id + '/' + this.orderId, {quantity: -quantity})
					.then(function(response){
						product.clicked = parseInt(product.clicked) - parseInt(quantity);
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
			deleteOrder() {
				var self = this;
				
				axios.delete('/order/' + this.orderId)
					.then(function(response){
						self.$snotify.warning('Deleted Order', self.orderId, {
						  timeout: notifyTimeout,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
					
				window.location.href = '/sale';
			}
		}
	}
</script>