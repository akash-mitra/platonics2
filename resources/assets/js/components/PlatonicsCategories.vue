<template>

    <div class="font-light bg-contour-teal">
        <h1 class="px-4 sm:px-8 py-4 font-hairline">
                Categories 
                
                <a href="/admin/categories/create" class="no-underline text-blue-light text-sm float-right" style="line-height: 2.5em">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                            Add Category
                </a>
        </h1>

        <div class="px-4 sm:px-8 py-4">
                
                <div class="text-grey text-sm">
                        <input type="text" class="w-3/4" v-model="searchPhrase" placeholder="Type category name to filter">
                        <span class="w-1/4 float-right text-right">
                                <span class="hidden sm:inline-block">Showing</span> {{ filteredCategories.length }} of {{ categories.length }} top categories
                        </span>
                </div>
        </div>

        <div class="border-t">
                
                <tree-view :tree="filteredCategories" :listClass="listClass" :itemClass="itemClass"></tree-view>
        </div>
    </div>

    
</template>

<script>

    export default {
        
        data() {
            
            return {
                categories: [],
                listClass: 'list-reset',
                itemClass: 'bg-white',
                searchPhrase: ""
            }
        },

        created() {

            axios.get('/api/categories')

                .then(response => {
                    //this.buildTree(response.data)
                    this.categories = this.buildTree(response.data);
                })
        },

        computed: {

                filteredCategories: function () { 

                        return this.categories.filter(item => {
                                
                                if (this.searchPhrase.length <= 2) return item;
                                
                                return this.treeSearch(item, this.searchPhrase)
                        });
                }
        },

        methods: {

            buildTree: function (hierarchy_data) {
                    
                let nodes = [];

                /*
                 * Create a flat list of all the elements 
                 * called nodes.
                 **/
                for (let i=0; i<hierarchy_data.length; i++) {

                   let item = hierarchy_data.data[i]
                  
                   item["children"] = [];

                   nodes['_' + item.id] = item;

                   if (item.parent_id != null) {
                        
                        nodes['_' + item.parent_id].children.push(item)

                   }
                }

                // console.log(nodes);
                let tree = []
                
                let keys = Object.keys(nodes)

                for (let i=0; i < keys.length; i++)
                {
                        let node = nodes[keys[i]]

                        if (node.parent_id == null)
                                tree.push(node)
                }

                
                return tree
            },

            treeSearch: function (heystack, needle) {

                // if the needle is present in the name property of heystack
                // or in any of its childrens' name property, return true

                if (heystack.name.indexOf(needle) >= 0)  {
                    return true
                }

                for (let i = 0; i < heystack.children.length; ++i)
                {
                    let child = heystack.children[i]

                    if (this.treeSearch(child, needle))
                        return true
                }

                return false
                
            }
        }
    }
</script>
