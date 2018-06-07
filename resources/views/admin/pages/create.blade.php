@extends('layouts.app')

@section('page.css')
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet"> -->

<!-- <link rel="stylesheet" href="cdn.jsdelivr.net/medium-editor/latest/css/medium-editor.min.css"> -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/medium-editor/5.22.1/css/medium-editor.css">
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

@section('content')
    
    <div id="app">

        <div class="md:flex">
                <div class="md:w-2/3 h-screen bg-white">
                        <div class="px-2">
                                <div class="pt-2 border-b">
                                        <p class="text-xs text-grey pb-2">Title</p>
                                        <input type="text" class="bg-transparent w-full">
                                </div>

                                <div class="pt-4 border-b">
                                        <p class="text-xs text-grey pb-2">Summary</p>
                                        <textarea type="text" class="bg-transparent w-full"></textarea>
                                </div>

                                <medium-editor 
                                        :text='Some trxtejsbc ' 
                                        :options='{}' 
                                        v-on:edit='processEditOperation' 
                                        custom-tag='div'>
                                </medium-editor>
                        </div>
                        
                </div>

                <div class="md:w-1/3 md:h-screen md:border-l">
                        <h4>Authoring Tools</h4>
                </div>
        </div>

        
    </div>  

@endsection

@section('page.script')
        <script src="{{ mix('js/pages.create.js') }}"></script> 
        
@endsection
