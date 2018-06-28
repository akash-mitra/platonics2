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
    DELETE SOMETHING (media.destroy.by.id)
</p>
<div class='hide'>
    <form method="POST" action="{{ route('media.destroy', ['id' => 16]) }}">
        <input type="hidden" name="_method" value="DELETE" />
        {!! csrf_field() !!}
        <button class="btn btn-default">DELETE</button>
    </form>
</div>

<p>
    UPDATE SOMETHING
</p>
<div class='hide'>
    <form method="POST" action="{{ route('users.update', ['id' => 2]) }}">
        <input type="hidden" name="_method" value="PATCH" />
        {!! csrf_field() !!}
        <input id="type" type="text" name="type">
        <button class="btn btn-default">UPDATE</button>
    </form>
</div>


<p>TEST USER UPDATE</p>
@endsection
