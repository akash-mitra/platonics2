@extends('layouts.app')

@section('page.css')
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet"> -->


        <link rel="stylesheet" href="{{ mix('/css/medium-editor.css') }}">
        

        <style>

                .icon {
                        display: inline-block;
                        height: 1.15em;
                        width: 1.25em;
                        fill: currentColor;
                        vertical-align: middle;
                }

                .icon-sm {
                        display: inline-block;
                        height: 1em;
                        width: 1em;
                        fill: currentColor;
                        vertical-align: middle;
                }

                #content-div > div {
                        min-height: 200px;
                }

        </style>
@endsection

@section('header')

        <div class="my-3 px-6">
                <!-- <div class="float-left text-sm py-3 italic text-grey">Saved automatically...</div> -->
                
        </div>
@endsection

@section('content')
    
    <div id="app">

        <page-form page_id="{{ $id }}"></page-form>

        
    </div>  

@endsection

@section('page.script') 

        <script src="{{ mix('js/pages.form.js') }}"></script> 
        
@endsection
