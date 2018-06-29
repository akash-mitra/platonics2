@extends('layouts.frontend')

@section('subheader')
        
        <div class="bg-purple shadow-inner">
                <div class="flex justify-center container mx-auto">
                        
                        <div class="w-3/4 py-10">
                                <h3 class="text-purple-lightest text-4xl font-light my-2">{{ $category->name }}</h3>
                                <p class="text-purple-lighter leading-normal">{{ $category->description }}</p>
                        </div>
                </div>
        </div>
@endsection

@section('content')
        
        @if(count($category->subcategories) != 0)
        <div class="my-4">

                <h4 class="py-6 text-grey-darker text-2xl">
                        Sub-categories /
                </h4>

                <ul class="list-reset flex flex-wrap">
                        
                        @foreach($category->subcategories as $sc)
                                <li class="w-full md:w-1/2 mb-12">

                                        <div class="min-h-full max-w-sm mr-8 bg-white rounded overflow-hidden shadow-md hover:shadow-lg">
                                                
                                                <div class="px-6 py-8">
                                                        <div class="font-bold text-xl mb-2">
                                                                <a href="{{ route('frontend.category', $sc->id) }}" class="no-underline text-grey-darkest">
                                                                        {{ $sc->name }}
                                                                </a>
                                                        </div>
                                                        <p class="text-grey-darker text-base">
                                                                {{ $sc->description }}
                                                        </p>
                                                        
                                                        
                                                </div>
                                                
                                        </div>
                                </li>
                        @endforeach
                        
                </ul>
                
        </div>  
        @endif
                

        @if(count($category->pages) != 0)
        <div class="my-4">
                <h4 class="py-6 text-grey-darker text-2xl">
                        Pages /
                </h4>

                <ul class="list-reset flex flex-wrap">
                        @foreach($category->publishedPages as $page)
                                <li class="w-full md:w-1/2 mb-12">

                                        <div class="min-h-full max-w-sm mr-8 bg-white rounded overflow-hidden shadow-md hover:shadow-lg">
                                                <img class="w-full" src="{{ $page->media_url }}" alt="Page Image">
                                                <div class="px-6 py-4">
                                                        <div class="font-bold text-xl mb-2">
                                                                <a href="{{ route('frontend.page', $page->id) }}" class="no-underline text-grey-darkest">
                                                                        {{ $page->title }}
                                                                </a>
                                                        </div>
                                                        <p class="text-grey-darker text-base">
                                                                {{ $page->summary }}
                                                        </p>
                                                </div>
                                                <div class="px-6 py-4">
                                                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">#photography</span>
                                                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker mr-2">#travel</span>
                                                        <span class="inline-block bg-grey-lighter rounded-full px-3 py-1 text-sm font-semibold text-grey-darker">#winter</span>
                                                </div>
                                        </div>

                                </li>
                        @endforeach
                </ul>
        </div>  
        @endif

        
@endsection