<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>Bill</strong> for <strong>{{ order.order_object.name }}</strong>
	                	<div class="pull-right">
		                	<a v-if="order.status != 'PAID'" :href="'/order/' + orderId + '/orderhistory'" class="btn btn-sm btn-warning">Continue Order</a>
		                	<a v-if="order.status != 'PAID'" href="javascript:void(0)" v-on:click="deleteOrder()" class="btn btn-sm btn-danger">Delete</a>
	                	</div>
	                </div>
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-xs-6">
								<a href="/order" class="btn btn-sm btn-primary btn-block">Create</a>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<a href="/sale" class="btn btn-sm btn-info btn-block">Continue Sale</a>
							</div>
						</div>
					</div>
	                <div class="panel-body">
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
	                    		<strong>Total</strong>
	                    	</div>
	                    	<div class="col-md-2 col-sm-3 col-xs-2 product-name no-padding-side">
	                    		<strong>Total Price</strong>
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
	                </div>
	                <div class="panel-footer">
	                	<button v-if="order.status != 'PAID'" class="btn btn-primary" v-on:click="pay()">Paid</button>
	                	<a href="/sale" class="btn btn-info">Continue Sale</a>
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
				groupedHistories: [],
			}
		},
		created() {
			var self = this;
			
			axios.get('/order/' + this.orderId)
				.then(function(response){
					self.order = response.data;
				});
			
			axios.get('/orderhistory/' + this.orderId + '/grouped')
				.then(function(response){
					self.groupedHistories = response.data;
				});
		},
		methods: {
			moneyFormat,
			pay() {
				var self = this;
				
				axios.post('/order/' + this.orderId + '/pay')
					.then(function(response){
						self.order = response.data;
						
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