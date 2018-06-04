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
    
    @yield('page.css')

    <style>
        .active {
            background-color: transparent;
        }
        .btnSidebar {
            cursor: pointer;

        }
        @media (max-width: 1200px) {
            .nav-scrollbar-wrapper nav {
                flex-wrap: nowrap;
                overflow-x: auto;
                overflow-y: auto;
                white-space: nowrap;
            }    
        }
         
    </style>
</head>
<body class="font-sans">
    
    <div class="flex flex-wrap bg-white">

        <div class="hidden flex-none md:flex md:flex-col md:w-full xl:w-1/5  max-h-screen overflow-scroll" id="leftCol">

            <div class="nav-scrollbar-wrapper">

                <nav>
                
                    <ul class="flex xl:flex-col list-reset">
                        <li class="sticky pin-t pin-l h-16 flex flex-col justify-center">
                            <h3 class="pl-8 text-teal">
                                Platonics &nbsp;
                                
                            </h3>
                            
                        </li>
                        <li class="hidden xl:block">
                            <p>&nbsp;</p>
                            
                        </li>
                        <li>
                            <span class="hidden p-6 pb-2 xl:flex text-grey-dark font-bold text-xs uppercase">Content Management</span>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline active" href="/admin/categories">
                                 Categories
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="/admin/pages">
                                 Pages
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="/admin/tags">
                                Tags
                            </a>
                        </li>
                        <li>
                            <span class="hidden p-6 pb-2 xl:flex text-grey-dark font-bold text-xs uppercase">User Management</span>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                 Users
                            </a>
                        </li>                
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                 Service
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                 Subscription
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                 Comments
                            </a>
                        </li>
                        
                        <li>
                            <span class="hidden p-6 pb-2 xl:flex text-grey-dark font-bold text-xs uppercase">Relationship</span>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                 Mailing List
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                 Facebook
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                Twitter
                            </a>
                        </li>


                        <li>
                            <span class="hidden p-6 pb-2 xl:flex text-grey-dark font-bold text-xs uppercase">Analytics</span>
                        </li>


                        <li>
                            <span class="hidden p-6 pb-2 xl:flex text-grey-dark font-bold text-xs uppercase">Server Management</span>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                CDN
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                Storage
                            </a>
                        </li>
                        <li>
                            <a class="pl-8 py-6 xl:py-2 flex hover:text-teal text-grey-darker no-underline font-hairline" href="#">
                                Ads
                            </a>
                        </li>
                        <li>
                            <p>&nbsp;</p>
                        </li>
                    </ul>
                </nav>
            </div>
            

        </div><!-- end of left col -->

        <div class="flex-1 w-full xl:w-4/5">

            <div class="max-h-screen  overflow-scroll">

                <header class="h-16 bg-white border flex justify-between">
                    
                    
                    <div class="text-grey-darker w-2/3 p-6">
                        <input id="docsearch" 
                            class="w-full"
                            placeholder="Search" 
                            autocomplete="off" 
                            spellcheck="false" 
                            aria-autocomplete="list" 
                            aria-expanded="false"
                            dir="auto" 
                            type="text" />
                    </div>

                    <div class="text-grey-darker w-1/3 p-6 text-right">
                        Akash
                    </div>
                    
                </header>

                <main class="bg-white min-h-screen border-l" style="background-color: #FAFAFA">
                
                    
                    @yield('content')
                </main>
            </div>
            
        </div>
    </div>

    <!-- 

        
    -->
    
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script> 
    
    @yield('page.script')

    
    
</body>
</html>