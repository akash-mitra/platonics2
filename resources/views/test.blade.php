@extends('layouts.app')

@section('content')
<form class="form-horizontal" method="POST" action="{{ route('media.store') }}" files=true enctype="multipart/form-data">
    {{ csrf_field() }}

    <div>
        <label for="name">Name</label>
        <div>
            <input id="name" type="text" name="name">
        </div>
    </div>
    <div>
        <input type="file" name="file" id="file">
    </div>
    <button type="submit">Submit</button>
</form>

<p>
    DELETE SOMETHING
</p>

  
<div class='hide'>
    <form method="POST" action="{{ route('media.destroy', ['id' => 37]) }}">
        <input type="hidden" name="_method" value="delete" />
        {!! csrf_field() !!}
        <button class="btn btn-default">Delete</button>
    </form>
</div>
@endsection
