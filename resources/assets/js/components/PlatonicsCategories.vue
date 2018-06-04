<template>
    <div class="">
        <h1 class="px-8 py-4 font-hairline text-t2eal">
            <slot name="header"></slot>
        </h1>

        <div class="px-8 pb-6 text-grey-dark font-hairline">
            <slot name="description"></slot>
        </div>

        <tree-view class="border-t"
            :tree="categories" 
            :listClass="listClass" 
            :itemClass="itemClass" 
        >
        </tree-view>

    </div>
</template>

<script>

    export default {
        
        data() {
            
            return {
                categories: [],
                listClass: 'list-reset',
                itemClass: 'bg-white'
            }
        },

        created() {

            axios.get('/api/categories')

                .then(response => {
                    //this.buildTree(response.data)
                    this.categories = this.buildTree(response.data);
                })
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
            }
        }
    }
</script>
