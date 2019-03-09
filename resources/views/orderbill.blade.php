@extends('layouts.app')

@section('meta')
	<meta name="order-id" content="{{ $orderId }}">
@endsection

@section('content')
<orderbill></orderbill>
@endsection