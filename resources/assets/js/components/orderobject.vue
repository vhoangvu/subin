<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-map-marker"></span>
	                		 Area
                		 </strong>
            		 </div>
	
	                <div class="panel-body">
	                	<form class="form" onsubmit="return false">
	                		<vue-accordion :one-at-atime="true" type="info">
	                			<vue-panel type="primary" v-for="(orderObjectGroupName, orderObjectGroupCode) in orderObjectGroupNames" :header="orderObjectGroupName" :key="orderObjectGroupCode">
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
				                    		<input type="text" v-model="newOrderObject.name" placeholder="Name" class="form-control">
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-5 no-padding-side">
				                    		<button class="btn btn-primary" v-on:click="create(orderObjectGroupCode)">New</button>
				                    	</div>
				                    </div>
									<div class="row product-line" v-for="(orderObject, index) in orderObjects[orderObjectGroupCode]" :key="orderObject.id">
				                    	<div class="col-md-10 col-sm-10 col-xs-7">
				                    		<input type="text" v-model="orderObject.name" placeholder="Name" class="form-control">
				                    	</div>
				                    	<div class="col-md-2 col-sm-2 col-xs-5 no-padding-side">
				                    		<button class="btn btn-primary" v-on:click="update(orderObject)">Save</button>
				                    		<button class="btn btn-danger" v-on:click="deleteOrderObject(orderObject, orderObjectGroupCode, index)">Delete</button>
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
				
				newOrderObject: {},
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
			update(orderObject) {
				var self = this;
				
				axios.put('/orderobject/' + orderObject.id, {order_object: orderObject})
					.then(function(response){
						self.$snotify.success('Updated', orderObject.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			create(orderObjectGroupCode) {
				var self = this;
				
				this.newOrderObject.order_object_group_code = orderObjectGroupCode;
				axios.post('/orderobject', {order_object: this.newOrderObject})
					.then(function(response){
						if (self.orderObjects[response.data.order_object_group_code]) {
							self.orderObjects[response.data.order_object_group_code].unshift(response.data);
						} else {
							self.orderObjects[response.data.order_object_group_code] = [];
							self.orderObjects[response.data.order_object_group_code].unshift(response.data);
						}
						
						self.newOrderObject = {};
						
						self.$snotify.success('Added', response.data.name, {
						  timeout: 600,
						  position: 'leftTop',
						  showProgressBar: false,
						  closeOnClick: false,
						  pauseOnHover: false
						});
					});
			},
			deleteOrderObject(orderObject, orderObjectGroupCode, dataIndex) {
				var self = this;
				
				axios.delete('/orderobject/' + orderObject.id)
					.then(function(response){
						self.orderObjects[orderObjectGroupCode].splice(dataIndex, 1);
						
						self.$snotify.warning('Deleted', orderObject.name, {
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