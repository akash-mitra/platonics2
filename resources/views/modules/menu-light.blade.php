<div class="overflow-scroll flex items-center">

        <div class="nav-scrollbar-wrapper flex">
                <nav>
                        <ul class="flex justify-start list-reset">
                                
                                @foreach($menus as $menuItem => $menuId)
                                        
                                        <li>
                                                <a class="p-4 text-grey-lightest hover:text-white no-underline" 
                                                        href="{{ route('frontend.category', $menuId) }}"
                                                >{{ $menuItem }}</a>
                                        </li>
                                @endforeach
                                
                        </ul>
                        </nav>
        </div>
</div>

