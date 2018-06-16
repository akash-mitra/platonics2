<template>
    
        
        <div class="font-light bg-contour-teal">
                <h1 class="px-4 sm:px-8 py-4 font-hairline">
                        Pages 
                        
                        <a href="/admin/pages/create" class="no-underline text-blue-light text-sm float-right" style="line-height: 2.5em">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                                 Add Page
                        </a>
                </h1>

                <div class="border-b px-4 sm:px-8 py-4">
                        
                        <div class="shadow-inn1er text-grey text-sm">
                                <input type="text" class="w-3/4" v-model="searchPhrase" placeholder="Type category, title or author name to filter">
                                <span class="w-1/4 float-right text-right">
                                        <span class="hidden sm:inline-block">Showing</span> {{ filteredPages.length }} of {{ pages.length }}
                                </span>
                        </div>
                </div>

                <div class="bg-white">
                        <!--  -->
                        <div class="hidden sm:flex text-grey py-4 text-sm">
                                <div class="w-1/6 text-right pr-8">Actions</div>
                                <div class="w-1/2">Pages</div>
                                <div class="w-1/3 pl-8">Receptions</div>
                        </div>
                        <page-card v-for="(page, index) in filteredPages" :key="index" :page="page"></page-card>
                </div>
        </div>


</template>

<script>

    export default {
        
        data() {
            
            return {
                pages: [],

                searchPhrase: ""

            }
        },

        computed: {

                filteredPages: function () { 

                        return this.pages.filter(item => {
                                
                                if (this.searchPhrase.length <= 2) return item;
                                
                                if (
                                        item.title.indexOf(this.searchPhrase) >= 0
                                        ||
                                        item.metakey.indexOf(this.searchPhrase) >= 0
                                        ||
                                        item.categories.name.indexOf(this.searchPhrase) >= 0
                                        ||
                                        item.users.name.indexOf(this.searchPhrase) >= 0
                                        
                                ) return item;
                        });
                }
        },
        
        created() {

            axios.get('/api/pages')

                .then(response => {
                    
                    this.pages = response.data.data;
                })
        }
    }
</script>
