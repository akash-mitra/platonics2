<template>
    <form method="post" action="/categories" @submit.prevent="onSubmit" @keydown="errors.clear($event.target.name)" @change="errors.clear($event.target.name)">
                
                <div class="bg-white p-8 text-grey-darkest border-purple-lighter border-b-2">
                        <h1 class="font-hairline"><span v-text="ops"></span> Category: <span v-text="name"></span></h1>
                </div>

                <div class="flex">
                        
                        <div class="w-full md:w-2/3 bg-white py-4 px-8 h-screen">
                                
                                <input type="hidden" name="id" v-model="id" required>
                                
                                <div class="pt-4">
                                        <label class="block uppercase tracking-wide text-grey text-xs mb-2" for="grid-state">
                                                Name
                                        </label>

                                        <input v-model="name" name="name" placeholder="e.g. Sports, Science etc.." type="text" class="block appearance-none w-full bg-grey-lightest border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight">

                                        <p class="text-xs text-red my-1" v-if="errors.has('name')" v-text="errors.get('name')"></p>
                                </div>

                                <div class="pt-8">
                                        
                                        <label class="block uppercase tracking-wide text-grey text-xs mb-2" for="grid-state">
                                                Description
                                        </label>

                                        <textarea v-model="description" name="description" placeholder="e.g. We publish bi-weekly articles about this category..." class="block appearance-none w-full bg-grey-lightest border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight"></textarea>

                                        <p class="text-xs text-red my-1" v-if="errors.has('description')" v-text="errors.get('description')"></p>
                                </div>

                                <div class="pt-8">

                                        <label class="block uppercase tracking-wide text-grey text-xs mb-2" for="grid-state">
                                                Make this category under
                                        </label>

                                        <div class="relative">
                                                <select v-model="parent_id" name="parent_id" class="block appearance-none w-full bg-grey-lightest border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight">
                                                        <option disabled>Please select one</option>
                                                        <option value="">None - It is a top level category</option>
                                                        <option v-for="category in categories" :value="category.id" v-bind:key="category.id">
                                                                {{ category.name }}
                                                        </option>
                                                </select>
                                                <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                </div>
                                        </div>

                                        <p class="text-xs text-red my-1" v-if="errors.has('parent_id')" v-text="errors.get('parent_id')"></p>
                                </div>

                                <div class="pt-8">

                                        <label class="block uppercase tracking-wide text-grey text-xs mb-2" for="grid-state">
                                                Choose Access Level
                                        </label>

                                        <div class="relative">
                                                <select v-model="access_level" name="access_level" class="block appearance-none w-full bg-grey-lightest border border-grey-lighter text-grey-darker py-3 px-4 pr-8 rounded leading-tight">
                                                        <option disabled value="">Please select one</option>
                                                        <option value="F">Free</option>
                                                        <option value="M">Member Only</option>
                                                </select>
                                                <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                                </div>
                                        </div>

                                        <p class="text-xs text-red my-1" v-if="errors.has('access_level')" v-text="errors.get('access_level')"></p>

                                </div>

                                <div class="pt-8">
                                        <button type="submit" :disabled="errors.any()" v-bind:class="{ 'cursor-not-allowed opacity-50': errors.any()}" class="bg-purple text-white py-2 px-4 rounded hover:bg-green">Save Category</button>
                                        <a href="/admin/categories" class="float-right text-purple py-2 px-4 border border-purple rounded no-underline">Cancel</a>
                                </div>

                        
                        </div>


                        <div class="hidden md:flex flex-col w-1/3 bg-grey-lighter p-4 h-screen text-sm text-grey-darker shadow-inner">
                                <h4 class="p-4 text-purple font-light text-xs uppercase">Category URL</h4>
                                
                                <p class="mb-4 px-8 text-grey-darker">
                                        Name field will be used to create the category page url.
                                        For example, this page will be available at URI <code v-text="categoryUrl"></code>

                                </p>

                                <h4 class="p-4 text-purple font-light text-xs uppercase">Access Restrictions</h4>
                                
                                <p class="mb-4 px-8 text-grey-darker">
                                        If you want to give only the registered users of your blog access to the articles of this category, select "Member Only" in Access Level drop-down

                                </p>

        
                        </div>
                </div>
        </form>
</template>

<script>

    export default {

        props: ['category_id'],

        data() {
            
            return {
                id: this.category_id, 
                name: '', 
                description: '', 
                access_level: '', 
                parent_id: '',
                errors: new Errors(),
                categories: [],
                ops: this.category_id ? 'Edit' : 'Create' 
            }
        },

        created: function () {

                /*
                 * First populate the category selection drop-down
                 * with list of categories from server.
                 */
                axios.get('/categories').then(response => {
                        
                        this.categories = response.data.data

                        /*
                         * If the form is being displayed for "edit" purpose,
                         * then populate the form fields (data) with 
                         * respective values of the selected category.
                         */      
                        if (this.ops === 'Edit') {

                                let category = this.searchCategoryById(this.id)
                                this.populateForm (category);
                        }
                        /*
                         * If the form is being displayed for new category
                         * creation and if parent category is provided,
                         * populate the category selection dropdown.
                         */
                        else {

                                this.parent_id = this.getUrlParameter('parent_id')

                        }

                })
        },

        computed: {
                categoryUrl: function () {
                        return '/' + this.name.replace(/\s+/g, '-').toLowerCase()
                }
        },

        methods: {

                /*
                 * Searches a category in the categories data 
                 * by a given id.
                 */
                searchCategoryById: function (id) {
                        let category = this.categories.filter(c => c.id == id);
                        return category[0];
                },


                populateForm: function (category) {
                        this.name = category.name
                        this.description = category.description
                        this.access_level = category.access_level
                        this.parent_id = category.parent_id
                },

                onSubmit: function () {
                        
                        axios.request({
                             'url':   '/categories/' +  this.$data.id, 
                             'method': this.ops === 'Edit' ? 'patch' : 'post',
                             'data': this.$data
                        })
                        .then(response => {
                                
                                this.id = response.data.id
                                
                                location.href = '/admin/categories'
                        })
                        .catch(error => {

                                this.errors.record(error.response.data.errors)
                                
                        })
                },

                getUrlParameter: function (name) {
                        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                        var results = regex.exec(location.search);
                        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
                }
        }
    }
</script>
