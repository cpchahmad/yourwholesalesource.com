@extends('layout.shopify')
@section('content')
    <div class="content">
        <h4>Hi {{auth()->user()->name}}</h4>
    </div>
@endsection
