@extends('layouts.app')

@section('page.css')
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet"> -->

    <style>
        .icon {
                display: inline-block;
                height: 1.15em;
                width: 1.25em;
                fill: currentColor;
                vertical-align: middle;
        }

    </style>
@endsection

@section('header')

        @include('partials.head-search')
@endsection

@section('content')
    
<div id="app">
        
        <category-form category_id="{{ $id }}"></category-form> 
</div>

        
    

@endsection

@section('page.script')


<script src="{{ mix('js/categories.form.js') }}"></script> 


@endsection
