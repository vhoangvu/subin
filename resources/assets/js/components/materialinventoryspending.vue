<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                	<strong>
	                		<span class="glyphicon glyphicon-usd"></span>
	                		 Report Inventory Cost 
                		 </strong>
            		 </div>
	                <div class="panel-heading">
	                	<button class="btn btn-primary" v-on:click="updateToday()">Today</button>
	                	<button class="btn btn-info" v-on:click="updateThisWeek()">This Week</button>
	                	<button class="btn btn-warning" v-on:click="updateThisMonth()">This Month</button>
	                </div>
	                <div class="panel-heading">
	                	<form class="form-inline" onsubmit="return false">
	                		<div class="form-group">
		                		<label for="from">From Date:</label>
							    <vue-datepicker v-model="from" 
							      			:format="vuetrapFrontendFormat" 
							      			:clear-button="true" 
							      			placeholder="From Day"
							      			lang="en"></vue-datepicker>
							</div>
							<div class="form-group">
								<label for="to">To Date:</label>
							    <vue-datepicker v-model="to" 
							      			:format="vuetrapFrontendFormat" 
							      			:clear-button="true" 
							      			placeholder="To Date"
							      			lang="en"></vue-datepicker>
							</div>
							<button class="btn btn-primary" v-on:click="loadReport(false)">View</button>
						</form>
	                </div>
	
	                <div class="panel-body">
	                	<div class="row product-line">
	                    	<div class="col-md-1 col-sm-1 col-xs-1 product-name">
	                    		<strong>#</strong>
	                    	</div>
	                    	<div class="col-md-4 col-sm-4 col-xs-4 product-name no-padding-side">
	                    		<strong>Name</strong>
	                    	</div>
	                    	<div class="col-md-1 col-sm-1 col-xs-1 product-name no-padding-side">
	                    		<strong>Total</strong>
	                    	</div>
	                    	<div class="col-md-1 col-sm-3 col-xs-3 product-name">
	                    		<strong>Cost</strong>
	                    	</div>
		           		</div>
		           		<template v-for="(dateData, date) in report.data">
			           		<div class="row product-line">
			           			<div class="col-md-3 col-sm-3 col-xs-3 product-name">
		                    		<strong>Date {{ frontendDateFormat(date) }}</strong>
		                    	</div>
		                    	<div class="col-md-3 col-sm-3 col-xs-3 product-name no-padding-side">
		                    		<strong class="pull-right">Total</strong>
		                    	</div>
			            		<div class="col-md-1 col-sm-4 col-xs-4 product-name">
		                    		<strong>{{ moneyFormat(report.price_sum[date]) }}</strong>
		                    	</div>
			           		</div>
			           		<div class="row product-line" v-for="(data, index) in dateData">
		                    	<div class="col-md-1 col-sm-1 col-xs-1 product-name">
		                    		{{ index + 1 }}
		                    	</div>
		                    	<div class="col-md-4 col-sm-4 col-xs-4 product-name no-padding-side">
		                    		{{ data.name }}
		                    	</div>
		                    	<div class="col-md-1 col-sm-1 col-xs-1 product-name no-padding-side">
		                    		{{ data.quantity }}
		                    	</div>
		                    	<div class="col-md-1 col-sm-3 col-xs-3 product-name">
		                    		{{ moneyFormat(data.price) }}
		                    	</div>
			           		</div>
		           		</template>
		           		<div class="row product-line summary">
		            		<div class="col-md-6 col-sm-6 col-xs-6 product-name">
	                    		<strong class="pull-right">Total From {{ from }} To {{ to }}</strong>
	                    	</div>
		            		<div class="col-md-1 col-sm-4 col-xs-4 product-name">
	                    		<strong>{{ moneyFormat(report.price_sum_sum) }}</strong>
	                    	</div>
		            	</div>
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
				vuetrapFrontendFormat,
				frontendFormat,
				backendFormat,
				report: {},
				
				from: today,
				to: today,
			}
		},
		created() {
			this.loadReport(false);
		},
		methods: {
			frontendDateFormat,
			backendDateFormat,
			moneyFormat,
			loadReport(exportExecel) {
				var self = this;
				
				axios.get('/materialinouthistory/reportinventoryspending', {params: {
													from: this.backendDateFormat(this.from), 
													to: this.backendDateFormat(this.to),
													export_excel: exportExecel,
													}
												})
					.then(function(response){
						self.report = response.data;
					});
			},
			updateToday() {
				this.from = moment().format(frontendFormat);
				this.to = moment().format(frontendFormat);
				this.loadReport(false);
			},
			updateThisWeek() {
				this.from = moment().startOf('week').format(frontendFormat);
				this.to = moment().endOf('week').format(frontendFormat);
				this.loadReport(false);
			},
			updateThisMonth() {
				this.from = moment().startOf('month').format(frontendFormat);
				this.to = moment().endOf('month').format(frontendFormat);
				this.loadReport(false);
			},
			exportExcel() {
				var params = {
								from: this.backendDateFormat(this.from), 
								to: this.backendDateFormat(this.to),
								export_excel: true,
				};
				window.location.href = '/materialinouthistory/reportinventoryspending?' + $.param(params);
			}
		}
	}
</script>