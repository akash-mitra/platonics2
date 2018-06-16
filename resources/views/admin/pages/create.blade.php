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

@section('content')
    
    <div id="app">

        <div class="md:flex">
                <div class="md:w-2/3 min-h-screen bg-white px-6 py-2">
                        
                        <div class="pt-2">
                                <p class="text-xs text-grey-dark pb-2">
                                        Title
                                </p>

                                <input v-model="title" type="text" class="p-2 rounded w-full border" style="background-color: #f8fafc">

                                <p class="text-xs text-right text-grey-dark my-1">
                                        
                                        <span v-if="title.length>80">
                                                Recommended: <i class="text-green">50 &dash; 80</i> chars | 
                                                Current: <i class="text-red" v-text="title.length"></i>
                                        </span>

                                        &nbsp;
                                </p>
                                
                        </div>

                        <div class="pt-1">
                                <p class="text-xs text-grey-dark pb-2">
                                        Summary
                                </p>
                                
                                <textarea v-model="summary" type="text" class="p-2 rounded w-full border" style="background-color: #f8fafc"></textarea>

                                <p class="text-xs text-right text-grey-dark my-1">
                                        
                                        <span v-if="summary.length>210">
                                                Recommended: <i class="text-green">140 &dash; 210</i> chars | 
                                                Current: <i class="text-red" v-text="summary.length"></i>
                                        </span>

                                        &nbsp;
                                </p>
                        </div>

                        <div class="pt-1">
                                <p class="flex text-xs text-grey-dark pb-2">
                                        <span class="w-1/2">
                                                Body
                                        </span>
                                        <span class="w-1/2 text-right">
                                                <span v-text="totalWords"></span> words | <span v-text="readingTime"></span>
                                        </span>
                                </p>
                                
                                <div id="content-div" class="text-sm py-2 rounded border-t" style="min-height: 200px;">
                                        <medium-editor :text='innerHTML' :options='options' custom-tag='div' v-on:edit='applyTextEdit'>
                                </div>
                                
                        </div>
                        
                
                        
                </div>

                <div class="md:w-1/3 p-6">

                        <div class="text-grey-dark text-sm">
                                <h3 class="py-2 mb-4 font-normal text-teal border-teal-light border-b">       
                                        <svg class="icon text-teal-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 10a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"/></svg>
                                        Publish Schedule
                                </h3>
                                
                                <p class="mb-3 text-xs">You may schedule your post to get automatically published in a future date</p>

                                <div class="flex text-xs">
                                        <span class="py-1 m-1">
                                                Publish this post from 
                                        </span>
                                        <button class="px-3 py-1 m-1 border border-blue rounded-full text-blue hover:bg-blue hover:text-white">
                                                 Now 
                                                 <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </button>
                                        
                                </div>
                        </div>

                        <div class="text-grey-dark text-sm">
                                <h3 class="py-2 mb-3 font-normal text-teal border-teal-light border-b">       
                                        <svg class="icon text-teal-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 10a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"/></svg>
                                        Keyword Analyzer
                                </h3>

                                <p class="mb-3 text-xs">Top keywords in the post. Click on them to add them as tags.</p>
                                
                                <div class="flex flex-wrap w-full text-xs">
                                        <div class="flex m-1 b1order text-blue border-blue-light rounded bg-blue-lightest">
                                                <span class="p-1">
                                                        <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                                                </span>
                                                <span class="p-1">Data Migration</span>  
                                        </div>

                                        <div class="flex m-1 b1order text-green border-green-light rounded bg-green-lightest">
                                                <span class="p-1">
                                                        <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/></svg>
                                                </span>
                                                <span class="p-1">SQL</span>  
                                        </div>

                                        <div class="flex m-1 b1order text-green border-green-light rounded bg-green-lightest">
                                                <span class="p-1">
                                                        <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/></svg>
                                                </span>
                                                <span class="p-1">Test Data</span>  
                                        </div>

                                        <div class="flex m-1 b1order text-blue border-blue-light rounded bg-blue-lightest">
                                                <span class="p-1">
                                                        <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                                                </span>
                                                <span class="p-1">Mock Data</span>  
                                        </div>

                                </div>

                                
                        </div>

                        

                        <!-- <div class="text-grey-dark text-sm">
                                <h3 class="py-2 mb-4 font-normal text-teal border-teal-light border-b">       
                                        <svg class="icon text-teal-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 10a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"/></svg>
                                        Keyword Analyzer
                                </h3>
                                
                                <p>
                                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Recusandae ex neque laborum magnam exercitationem tenetur tempore quia! Cupiditate, officia repudiandae. Cum nulla sint quasi, numquam exercitationem assumenda ad eveniet a.
                                </p>  
                        </div> -->
                </div>
        </div>

        
    </div>  

@endsection

@section('page.script') 

        <script src="{{ mix('js/pages.create.js') }}"></script> 
        
@endsection
