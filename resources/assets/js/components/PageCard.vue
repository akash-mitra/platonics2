<template>
        <div class="px-4 md:px-0 sm:flex py-8 border-t border-blue-lightest border-dotted">
                <div class="sm:w-1/6 sm:text-right pr-8 mb-4">
                
                        <p class="mb-1">
                                <a class="no-underline leading-normal text-blue-darker hover:text-blue text-sm" href="#">Show</a>
                        </p>
                        <p class="my-1">
                                <span class="leading-normal text-blue-darker hover:text-blue text-sm cursor-pointer" @click="togglePublish(page.id)" v-text="page.publish === 'Y'? 'Unpublish': 'Publish'"></span>
                        </p>
                        
                        <p class="my-1">
                                <a class="no-underline leading-normal text-blue-darker hover:text-blue text-sm" href="#">Comments</a>
                        </p>

                        <p class="hidden sm:block">
                                <a class="no-underline leading-normal text-blue-darker hover:text-blue text-sm" href="#">Modify Tags</a>
                        </p>
                        
                        <p class="mt-2 hidden sm:block" v-if="hasIssue()">
                                <span class="py-1 px-2 bo1rder roun1ded-full border-orange text-xs text-orange text-center">
                                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 5h2v6H9V5zm0 8h2v2H9v-2z"/></svg>
                                        Issue <span class="hidden md:inline-block">Detected</span>
                                </span>
                        </p>
                
                
                </div>
                <div class="sm:w-1/2 pr-4">
                        <p class="w-full text-teal text-sm">
                                {{page.categories.name}}
                                <svg class="icon text-teal-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"/></svg>
                        </p>
                        <p class="w-full font-light">
                                <a class="text-blue-darker text-lg no-underline hover:text-blue" :href="getEditUrl(page.id)" v-text="page.title"></a>
                                <span class="w-full text-grey text-sm italic leading-loose" v-text="page.summary.substr(0,100)"></span>
                        </p>
                        
                        <p class="w-full text-purple-light text-xs leading-loose">
                                <svg class="icon text-purple-lighter" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg>
                                Created on <span v-text="page.created_at.substr(0, 10)"></span> by <span v-text="page.users.name">Akash</span>
                        </p>

                        <p class="mt-4 hidden sm:block">

                                <span v-for="(tag, index) in page.metakey.split(',')" :key="index" 
                                        class="py-1 px-2 mr-2 rounded bg-blue-lightest text-center">
                                                <a class="text-sm text-blue no-underline" :href="'/tags/' + tag.trim()">{{ tag }}</a>
                                </span>
                                
                        </p>
                </div>
                <div class="hidden sm:block sm:w-1/3 px-8">
                        <div class="flex">
                                <div class="w-1/2 text-center mr-2">
                                        <div class="py-6 rounded text-teal text-3xl bg-grey-lightest shadow-inner">
                                                2K
                                        </div>
                                        <div class="text-sm text-grey leading-loose">
                                                Page Views
                                        </div>
                                </div>
                                <div class="w-1/2 text-center ml-2">
                                        <div class="py-6 rounded text-teal text-3xl bg-grey-lightest shadow-inner">
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
        
        props: ["page"],

        methods: {
                getEditUrl: function (id) {
                        return '/pages/' + id + '/edit'
                },

                hasIssue: function() {
                        return false
                },

                togglePublish: function (id) {
                        this.$emit('toggle-publish', id)
                }

        }
    }
</script>
