<template>
        <div class="px-4 md:px-0 sm:flex py-8 border-t border-blue-lightest border-dotted">
                <div class="sm:w-1/6 sm:text-right pr-8 mb-4">
                
                        <p class="mb-1">
                                <a class="no-underline leading-normal text-blue-light hover:text-blue text-sm" href="#">Show</a>
                        </p>
                        <p class="my-1">
                                <a class="no-underline leading-normal text-blue-light hover:text-blue text-sm" href="#">Unpublish</a>
                        </p>
                        
                        <p class="my-1">
                                <a class="no-underline leading-normal text-blue-light hover:text-blue text-sm" href="#">Comments</a>
                        </p>

                        <p class="hidden sm:block">
                                <a class="no-underline leading-normal text-blue-light hover:text-blue text-sm" href="#">Modify Tags</a>
                        </p>
                        
                        <p class="mt-2 hidden sm:block" v-if="page.metadesc.length>100">
                                <span class="py-1 px-2 bo1rder roun1ded-full border-orange text-xs text-orange text-center">
                                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5h2v6H9V5zm0 8h2v2H9v-2z"/></svg>
                                        Issue <span class="hidden md:inline-block">Detected</span>
                                </span>
                        </p>
                
                
                </div>
                <div class="sm:w-1/2 pr-4">
                        <p class="w-full text-grey-dark text-sm">Migration > </p>
                        <p class="w-full text-blue-darker text-lg font-light leading-normal">
                                {{ page.title }}
                                <span class="w-full text-grey text-sm italic leading-loose">{{ page.summary.substr(1,60)}}</span>
                        </p>
                        
                        <p class="w-full text-grey-dark text-sm leading-loose">Written By Akash | 24-May-2018</p>

                        <p class="mt-4 hidden sm:block">

                                <span 
                                        v-for="(tag, index) in page.metakey.split(',')" :key="index" 
                                        class="py-1 px-2 mr-2 rounded bg-blue-lightest text-center">
                                        <a class="text-sm text-blue no-underline" href="#">{{ tag }}</a>
                                </span>
                                
                        </p>
                </div>
                <div class="hidden sm:block sm:w-1/3 px-8">
                        <div class="flex">
                                <div class="w-1/2 text-center mr-2">
                                        <div class="py-6 rounded bg-contour-teal text-blue-light text-3xl shadow-inner">
                                                2K
                                        </div>
                                        <div class="text-sm text-grey leading-loose">
                                                Page Views
                                        </div>
                                </div>
                                <div class="w-1/2 text-center ml-2">
                                        <div class="py-6 rounded bg-contour-teal text-blue-light text-3xl shadow-inner">
                                                $20
                                        </div>
                                        <div class="text-sm text-grey leading-loose">
                                                Ad Revenue
                                        </div>
                                </div>
                        </div>
                        
                        <p class="text-xs text-center mt-2 text-blue-light p-2">
                                
                                Reach your audience
                        </p>
                </div>
        </div>

</template>

<script>

    export default {
        
        props: [
            "page"
        ]
    }
</script>
