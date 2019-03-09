@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Welcome To Subin Shop</strong></div>
                <div class="panel-heading">
                	<div class="row">
                		<div class="col-md-3 col-sm-3 col-xs-12 product-name">
                			<div style="padding-top:10px;">You Need :</div>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/sale" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-shopping-cart"></span>
                				 Sale
                			</a>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/materialin" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-import"></span>
                				 Add Inventory
                			</a>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/materialout" class="btn btn-primary btn-block">
                			 	<span class="glyphicon glyphicon-export"></span>
                			 	 Take Inventory
                			</a>
                		</div>
                	</div>
                </div>
                <div class="panel-heading">
                	<div class="row">
                		<div class="col-md-3 col-sm-3 col-xs-12 product-name">
                			<div style="padding-top:10px;">Check :</div>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/bill" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-usd"></span>
                				 Waiting Bills
                			</a>
                		</div>
                	</div>
                </div>
                <div class="panel-heading">
                	<div class="row">
                		<div class="col-md-3 col-sm-3 col-xs-12 product-name">
                			<div style="padding-top:10px;">Update:</div>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/productview" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-cutlery"></span>
                				 Menu
                			</a>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/materialview" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-leaf"></span>
                				 Inventory
                			</a>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/orderobjectview" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-map-marker"></span>
                				 Area
                			</a>
                		</div>
                	</div>
                </div>
                <div class="panel-heading">
                	<div class="row">
                		<div class="col-md-3 col-sm-3 col-xs-12 product-name">
                			<div style="padding-top:10px;">Report:</div>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/salereport" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-signal"></span>
                				 Sale
                			</a>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/materialinventory" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-folder-close"></span>
                				 Inventory
                			</a>
                		</div>
                		<div class="col-md-3 col-sm-3 col-xs-12 home-button-div">
                			<a href="/materialinventoryspending" class="btn btn-primary btn-block">
                				<span class="glyphicon glyphicon-usd"></span>
                		 		 Cost
                		 	</a>
                		</div>
                	</div>
                </div>

                <div class="panel-body">
                	
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
