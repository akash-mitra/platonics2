@extends('layouts.app')

@section('content')
    
    <div id="app">

        <platonics-categories>
        
            <template slot="header">Data Categories</template>
            <template slot="description">Manage data categories for the aspera products</template>
            
        </platonics-categories>
        
    </div>  

@endsection

@section('page.script')
    <script src="{{ mix('js/categories.js') }}"></script> 
@endsection
