<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-map-marker"></span>
	                		 Order: 
	                	</strong>
	                	chọn 
	                	<strong>
	                		Area
	                	</strong>
                	</div>
	
	                <div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(orderObjectGroupName, orderObjectGroupCode) in orderObjectGroupNames" :header="orderObjectGroupName" :key="orderObjectGroupCode">
									<div class="row product-line">
				                    	<div class="col-md-3 col-sm-3 col-xs-12 product-name" v-for="(orderObject, index) in orderObjects[orderObjectGroupCode]" :key="orderObject.id">
				                			<button class="btn btn-info btn-block" v-on:click="createOrder(orderObject)">{{ orderObject.name }}</button>
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
				orderObjects: [],
				
				orderObjectGroupNames,
			}
		},
		created() {
			var self = this;
			
			axios.get('/orderobject')
				.then(function(response){
					self.orderObjects = response.data;
				});
		},
		methods: {
			createOrder(orderObject) {
				var self = this;
				
				axios.post('/order', {order_object: orderObject})
					.then(function(response){
						window.location.href = '/order/' + response.data.id + '/orderhistory';
					});
			},
		}
	}
</script>