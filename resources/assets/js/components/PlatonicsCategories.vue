<template>
    <div>
        <h1 class="py-6 font-hairline">
            <slot name="header"></slot>
        </h1>

        <div class="mb-8 pb-4 text-grey font-hairline">
            <slot name="description"></slot>
        </div>

        <div class="flex my-2 p-2 border bg-grey-lightest" v-for="(item, index) in categories" :key="index" v-bind:style="{ marginLeft: 10 * (item.level - 1) + 'px'}">

            <div class="flex items-start py-2 w-1/3">
                <span v-for="n in item.level - 1" :key="n">.</span>
                <input type="text" class="bg-grey-lightest flex-1 w-full pb-2" v-model="item.name">
            </div>

            <div class="py-2 pr-4 w-1/2">
                <textarea class=" bg-grey-lightest w-full" v-model="item.description"></textarea>
            </div>

            <div class="py-2 w-1/6" v-text="item.access_level"></div>

        </div>

    </div>
</template>

<script>
    export default {
        
        data() {
            
            return {
                categories: []
            }
        },

        created() {

            axios.get('/api/categories')

                .then(response => {
                    
                    this.categories = this.flatten(response.data);
                })
        },

        methods: {

            /**
            * Flattens the tree structure in such a way that children elements
            * always come after the parent element. Also, each child element
            * is added with a "level" indicator that shows the depth level
            * of the element. The function requires the data to always 
            * have the parent category appear before its child.
            */
            flatten: function (hierarchy_data) {
                let flatten_tree = [];

                for(let i=0; i<hierarchy_data.length; i++) {
                    
                    let element = hierarchy_data.data[i];

                    if (element.parent_id === null) {
                        element["level"] = 1;
                        flatten_tree.push(element);
                    }
                    else {
                        for (let i = 0; i < flatten_tree.length; i++) {
                            let c = flatten_tree[i];

                            if (c.id === element.parent_id) {
                                element["level"] = c.level + 1;
                                flatten_tree.splice(i + 1, 0, element);
                                break;
                            }
                        }
                    }
                };
                return flatten_tree;
            }
        }
    }
</script>
