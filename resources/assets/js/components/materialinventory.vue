<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-folder-close"></span>
	                		 Report Inventory
                		 </strong>
            		 </div>
	                
	                <div class="panel-body">
	                	<div class="row product-line">
	                    	<div class="col-md-1 col-sm-1 col-xs-1 product-name no-padding-side">
	                    		<strong>#</strong>
	                    	</div>
	                    	<div class="col-md-5 col-sm-5 col-xs-5 product-name no-padding-side">
	                    		<strong>Name</strong>
	                    	</div>
	                    	<div class="col-md-3 col-sm-3 col-xs-3 product-name no-padding-side">
	                    		<strong>Unit</strong>
	                    	</div>
	                    	<div class="col-md-3 col-sm-3 col-xs-3 product-name no-padding-side">
	                    		<strong>Total</strong>
	                    	</div>
		           		</div>
		           		<template v-for="(data, index) in report.data">
			           		<div class="row product-line">
			           			<div class="col-md-1 col-sm-1 col-xs-1 product-name no-padding-side">
		                    		{{ index + 1 }}
		                    	</div>
		                    	<div class="col-md-5 col-sm-5 col-xs-5 product-name no-padding-side">
		                    		{{ data.name }}
		                    	</div>
			            		<div class="col-md-3 col-sm-3 col-xs-3 product-name no-padding-side">
		                    		{{ data.unit_code ? unitNames[data.unit_code] : '' }}
		                    	</div>
		                    	<div class="col-md-3 col-sm-3 col-xs-3 product-name no-padding-side">
		                    		{{ data.quantity }}
		                    	</div>
			           		</div>
		           		</template>
	                </div>
	                <div class="panel-footer">
	                	<button class="btn btn-primary" v-on:click="exportExcel()">Export Excel</button>
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
				report: {},
				
				unitNames,
			}
		},
		created() {
			this.loadReport(false);
		},
		methods: {
			loadReport(exportExecel) {
				var self = this;
				
				axios.get('/materialinouthistory/reportinventory', {params: {
																			export_excel: exportExecel,
																		}
												})
					.then(function(response){
						self.report = response.data;
					});
			},
			exportExcel() {
				var params = {
								export_excel: true,
				};
				window.location.href = '/materialinouthistory/reportinventory?' + $.param(params);
			}
		}
	}
</script>