<template>

    <div>
        <h1 class="px-4 sm:px-8 py-4 bg-white font-hairline">
                <span v-text="heading"></span>
                
                <a href="/admin/tags/create" class="no-underline text-sm float-right bg-purple text-white pqy-1 px-4 rounded hover:bg-green" style="line-height: 2.5em">
                        
                            Add Tags
                </a>
        </h1>

        <div class="px-4 sm:px-8 py-4 bg-white border-purple-lighter border-b-2">
                
                <div class="text-grey text-sm">
                        <input type="text" class="w-3/4" v-model="searchPhrase" placeholder="Type name to search...">
                        <span class="w-1/4 float-right text-right">
                                <span class="hidden sm:inline-block">Showing</span> {{ filteredTags.length }} of {{ tags.length }} Tags
                        </span>
                </div>
        </div>

        <div class="block sm:flex flex-wrap">
                
                <div v-for="(tag, index) in filteredTags" :key="index" class="w-full sm:w-1/2 p-2">
                        <div class="bg-white p-2 border-b">
                        <h3 class="text-blue-darker text-lg font-light leading-normal" v-text="tag.name"></h3>
                        <p class="text-sm text-grey-darker py-1" v-text="tag.description"></p>
                        <span class="text-xs py-4">
                                <a :href="editURL(tag.id)" class="no-underline text-purple hover:text-blue">Edit</a>
                                &nbsp; | &nbsp;
                                <a :href="showURL(tag.name)" class="no-underline text-purple hover:text-blue">Show</a>
                        </span>
                        </div>
                </div>
                
        </div>

        

    </div>

    
</template>

<script>

    export default {
        
        data() {
            
            return {
                tags: [],
                searchPhrase: '',
                heading: 'Tags'
            }
        },

        created() {

            this.heading = 'Loading Tags. Please wait...'

            axios.get('/api/tags')

                .then(response => {
                    
                    this.tags = response.data.data
                    this.heading = 'Tags'
                })

                .catch(error => {
                        this.heading = 'Error loading Tags! Try later.'
                })
        },

        computed: {

                filteredTags: function () { 

                        let self = this
                        return this.tags.filter(item => {
                                
                                if (self.searchPhrase.length <= 1) return item;
                                
                                if (item.name.toLowerCase().indexOf(self.searchPhrase) >= 0)  {
                                        return item
                                }
                        });
                }
        },
        methods: {
                editURL: function (id) { return '/admin/tags/' + id + '/edit' },
                showURL: function (name) { return '/tags/' + name }
        }
    }
</script>
