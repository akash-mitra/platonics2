<div class="w-full max-w-lg mx-auto bg-white border shadow-lg rounded my-8">
        <div id="comment-card">
                <comments commentable="pages" commentableid="{{$parameters['object_id']}}"></comments>
        </div>
</div>

@section('page.script')
        <script src="{{ mix('js/comments-frontend.js') }}"></script> 
@endsection
