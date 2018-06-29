<template>
    
        
        <div class="font-light bg-white">
                <h1 class="px-4 sm:px-8 py-4 font-hairline">
                        Pages 
                        
                        <a href="/admin/pages/create" class="no-underline text-sm float-right bg-purple text-white pqy-1 px-4 rounded hover:bg-green" style="line-height: 2.5em">
                                
                                 Add Page
                        </a>
                </h1>

                <div class="px-4 sm:px-8 py-4 border-purple-lighter border-b-2">
                        
                        <div class="shadow-inn1er text-grey text-sm">
                                <input type="text" class="w-3/4" v-model="searchPhrase" placeholder="Type category, title or author name to search...">
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
                        <page-card 
                                v-for="(page, index) in filteredPages" 
                                :key="index" 
                                :page="page"
                                v-on:toggle-publish="togglePublish" 
                        ></page-card>
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
                                        item.category.name.indexOf(this.searchPhrase) >= 0
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
        },

        methods: {
        
                togglePublish: function (id) {

                        axios.request({
                                'url': '/admin/pages/' + id + '/publish',
                                'method': 'put',
                        })
                        .then(response => {

                                let eventOriginatedFromPage = this.pages.filter(page => { if (page.id === id) return page })[0]

                                eventOriginatedFromPage.publish = (eventOriginatedFromPage.publish === 'Y' ? 'N' : 'Y')

                        })
                        .catch(error => {
                                
                                alert('Some error occurred. Try again later.')

                        })


                }
        }
        
    }
</script>
