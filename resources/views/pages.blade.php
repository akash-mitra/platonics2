@extends('layouts.frontend')

@section('page.css')
<style>
        .main-content h3 {
                margin-top: 2em;
                margin-bottom: 0.5em;
                color: #3D4852;
                font-size: 1.5em;
                
        }

        .main-content p {
                font-family: Constantia, Lucida Bright, Lucidabright, Lucida Serif, Lucida, DejaVu Serif, Bitstream Vera Serif, Liberation Serif, Georgia, serif;
                line-height: 1.5em;
        }
</style>
@endsection

@section('subheader')
        
        
        <div class="w-full -mt-20 flex h-screen justify-center items-center"  style="@if (! empty($page->media_url)) background-image: url('{{ $page->media_url }}'); @endif background-size: cover; box-shadow: inset 0 0 0 2000px rgba(0,0,0,0.75)">
                <div class="max-w-lg w-full mx-auto">
                        <div class="text-white my-4">

                                <span class="text-blue">{{ $page->updated_at->diffForHumans() }}</span> 
                                <span class="text-grey">under</span>
                                <a class="text-blue no-underline" href="{{ route('frontend.category', $page->category_id) }}">{{ $page->category->name }}</a>
                        </div>
                        <div class="my-4 font-bold text-5xl text-white leading-tight">
                                {{ $page->title }}
                        </div>
                        <div class="my-8 text-grey text-2xl italic leading-normal font-serif">
                                        {{ $page->summary }}
                        </div>

                        <div class="flex items-center">
                                <img class="w-16 h-16 border border-white rounded-full mr-4" src="https://pbs.twimg.com/profile_images/885868801232961537/b1F6H4KC_400x400.jpg" alt="Avatar of User">
                                <div class="text-sm">
                                        <p class="text-grey text-2xl leading-none">{{ $page->users->name }}</p>
                                        
                                </div>
                        </div>
                </div>
        </div>
        
@endsection

@section('content')
        

        @if($page->access_level === 'F')
        <div class="max-w-lg bg-white mx-auto my-6 -mt-16 rounded overflow-hidden shadow-lg">
                <div class="p-8">
                        <div class="text-grey-darker main-content">
                                {!! $page->contents->body !!}
                        </div>
                </div>
        </div>
        @else
        <div class="max-w-lg bg-white mx-auto my-6 -mt-20 rounded overflow-hidden shadow-lg">
                <div class="p-8">
                        <p class="text-sm text-2xl text-grey-dark flex items-center">
                                <svg class="fill-current text-grey w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M4 8V6a6 6 0 1 1 12 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-8c0-1.1.9-2 2-2h1zm5 6.73V17h2v-2.27a2 2 0 1 0-2 0zM7 6v2h6V6a3 3 0 0 0-6 0z" /></svg>
                                Members only
                        </p>
                        <p class="py-6 text-grey">Hi there! Some of our pages are exclusively reserved for our members. Please login to continue.</p>
                        

                </div>
        </div>
                
        @endif
        

        
@endsection