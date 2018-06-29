<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Platonics') }}</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}"> 
    
    

    <style>
        .active {
            background-color: transparent;
        }
        .btnSidebar {
            cursor: pointer;

        }
        .icon {
                display: inline-block;
                height: 1.15em;
                width: 1.25em;
                fill: currentColor;
                vertical-align: middle;
        }
        
        .nav-scrollbar-wrapper nav {
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: auto;
            white-space: nowrap;
        }    
    
         
    </style>

    @yield('page.css')
</head>
<body class="{{ $styles['body']['class']}}">
    
<div>

    <!-- HEADER MODULE -->
    @if($styles['header']['display'])
        <div class="{{$styles['header']['class']}}">
    
                @yield('header')

                @if(array_key_exists('header', $modules))
                    @foreach($modules['header'] as $module)
                        @include('modules.' . $module, ['parameters' => $parameters])
                    @endforeach
                @endif        
            
        </div>
    @endif
    <!-- HEADER MODULE -->



    <!-- SUB-HEADER MODULE -->
    @if($styles['subheader']['display'])
        <div class="{{$styles['subheader']['class']}}">
    
                @yield('subheader')

                @if(array_key_exists('subheader', $modules))
                    @foreach($modules['subheader'] as $module)
                        @include('modules.' . $module, ['parameters' => $parameters])
                    @endforeach
                @endif
                
        </div>
    @endif
    <!-- SUB-HEADER MODULE -->


    <div class="container mx-auto">
        <div class="sm:flex w-full justify-center">

            <!-- LEFT SIDE COLUMN -->
            @if($styles['left']['display'])
                <div class="{{$styles['left']['class']}}">
                    
                    @yield('left')

                    @if(array_key_exists('left', $modules))
                        @foreach($modules['left'] as $module)
                            @include('modules.' . $module, ['parameters' => $parameters])
                        @endforeach
                    @endif

                </div>
            @endif
            <!-- LEFT SIDE COLUMN -->


            <!-- CENTER COLUMN -->
            @if($styles['center']['display'])
                <div class="{{$styles['center']['class']}}">

                    @yield('content')

                    @if (array_key_exists('center', $modules))
                        @foreach($modules['center'] as $module)
                            @include('modules.' . $module, ['parameters' => $parameters])
                        @endforeach
                    @endif
                </div>
            @endif
            <!-- CENTER COLUMN -->


            <!-- RIGHT SIDE COLUMN -->
            @if($styles['right']['display'])
                <div class="{{$styles['right']['class']}}">
                    
                    @yield('right')

                    @if(array_key_exists('right', $modules))
                        @foreach($modules['right'] as $module)
                            @include('modules.' . $module, ['parameters' => $parameters])
                        @endforeach
                    @endif

                </div>
            @endif
            <!-- RIGHT SIDE COLUMN -->

        </div>
    </div>


    <!-- FOOTER MODULE -->
    @if($styles['footer']['display'])
        <div class="{{$styles['footer']['class']}}">
            
            @yield('footer')

            @if(array_key_exists('footer', $modules))
                @foreach($modules['footer'] as $module)
                    @include('modules.' . $module, ['parameters' => $parameters])
                @endforeach
            @endif

        </div>
    @endif
    <!-- FOOTER MODULE -->
</div>

<script src="{{ mix('js/app.js') }}"></script> 
@yield('page.script')

</body>
</html>