@extends('layouts.app')

@section('page.css')
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet"> -->
@endsection

@section('content')
    
    <div id="app">

        <platonics-categories>
        
            <template slot="header">Data Categories</template>
            <template slot="description">Create and Manage categories for grouping your pages logically.</template>
            
        </platonics-categories>
        
    </div>  

@endsection

@section('page.script')
    <script src="{{ mix('js/categories.js') }}"></script> 
@endsection
