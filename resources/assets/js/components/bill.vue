<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-usd"></span>
	                		 Waiting Bills
                		 </strong>
            		 </div>
	
	                <div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(orderObjectGroupName, orderObjectGroupCode) in orderObjectGroupNames" :header="orderObjectGroupName + ' (' + (orders.data[orderObjectGroupCode] ? orders.data[orderObjectGroupCode].length : 0) + ')'" :key="orderObjectGroupCode">
									<div class="row product-line">
				                    	<div class="col-md-3 col-sm-3 col-xs-12 product-name" v-for="(order, index) in orders.data[orderObjectGroupCode]" :key="order.id">
				                			<a :href="'/order/' + order.id + '/bill'" class="btn btn-info btn-block">{{ order.order_object.name }}: {{ moneyFormat(orders.price_sum[order.id]) }}</a>
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
				orders: {
					data: {},
					price_sum: {}
				},
				
				orderObjectGroupNames,
			}
		},
		created() {
			var self = this;
			
			axios.get('/order/list')
				.then(function(response){
					self.orders = response.data;
				});
		},
		methods: {
			moneyFormat,
		}
	}
</script>