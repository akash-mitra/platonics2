@extends('layouts.app')

@section('content')
    
<div class="w-full max-w-screen-lg mx-auto px-6">
    <div class="lg:flex -mx-6">
        <div id="sidebar" class="hidden absolute z-90 top-16 bg-white w-full border-b -mb-16 lg:-mb-0 lg:static lg:bg-transparent lg:border-b-0 lg:pt-0 lg:w-1/4 lg:block lg:border-0 xl:w-1/5">
            Sidebar
        </div>
        <div class="min-h-screen w-full lg:w-3/4 xl:w-4/5">
            <div id="content">
                <div id="app" class="flex">
                    <div class="pt-24 pb-8 lg:pt-28 w-full">
                        <div class="markdown mb-6 px-6 max-w-lg mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:px-12 xl:w-3/4">
                            <h1>Heading</h1> 
                            <div class="text-xl text-grey-dark mb-4">
                                A very useful sub-heading goes here
                            </div>
                        </div>

                        <div class="flex">
                            <div class="markdown px-6 xl:px-12 w-full max-w-lg mx-auto lg:ml-0 lg:mr-auto xl:mx-0 xl:w-3/4">
                                <div class="bg-blue-lightest border-l-4 border-blue-light text-blue-darkest px-4 py-3 mb-4">
                                    <div class="flex">
                                        Some metadata
                                    </div>
                                </div> 
                                <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus velit, sapiente quo ipsa quia vero ratione dolore saepe cupiditate laborum autem dolores eveniet necessitatibus? Voluptate ea voluptatum fuga sint laborum.
                                </p>


                                <div class="mt-4 border-t border-b border-grey-light overflow-hidden relative">
                                    <div class="overflow-y-auto">
                                        <table class="w-full text-left table-collapse">
                                            <thead>
                                                <tr>
                                                    <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">Column 1</th> 
                                                    <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">Column 2</th>
                                                </tr>
                                            </thead> 
                                            <tbody class="align-baseline">
                                                <tr>
                                                    <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">America</td> 
                                                    <td class="p-2 border-t border-grey-light font-mono text-xs text-blue-dark whitespace-pre">England</td>
                                                </tr> 
                                            </tbody>
                                        </table>
                                    </div>
                                </div> 
                                
                            </div> 
                            <div class="hidden xl:text-sm xl:block xl:w-1/4 xl:px-6">
                                <div class="flex flex-col justify-between overflow-y-auto sticky top-16 max-h-(screen-16) pt-12 pb-4 -mt-12">
                                    <ul class="list-reset mb-8">
                                        <li class="mb-2">
                                            <a href="#" class="text-grey-dark hover:text-grey-darkest">Reference Menu 1</a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="#" class="text-grey-dark hover:text-grey-darkest">Reference Menu 2</a>
                                        </li>
                                    </ul> 
                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
