<template>
    <div>
        
        <ul v-for="(node, index) in tree" :key="index" :class="listClass">
                <li :class="itemClass">
                        <div class="py-4 px-8 border-dotted border-blue-lightest border-b">
                                <div :class="'ml-' + 4 * level">

                                        
                                                
                                                <p v-if="parentName" class="text-xs uppercase text-teal-light mb-1">
                                                     {{ parentName }} &gt; <br>
                                                </p>
                                                <p class="text-blue-darker text-lg font-light leading-normal">
                                                        {{node.name}} 
                                                        <span class="text-xs text-grey-dark py-1 px-2 ml-2 border rounded">
                                                                 {{ node.children.length }} items
                                                        </span>

                                                        <span class="text-xs px-4">
                                                                <a :href="editCatURL(node.id)" class="no-underline text-purple hover:text-blue">Edit</a>
                                                                &nbsp; | &nbsp;
                                                                <a :href="addPageURL(node.id)" class="no-underline text-purple-light hover:text-blue">Add Page</a>
                                                                &nbsp; | &nbsp;
                                                                <a :href="addSubCatURL(node.id)" class="no-underline text-purple-light hover:text-blue">Add Sub-category</a>
                                                        </span>
                                                </p>
                                                
                                                
                                        
                                        <p class="text-grey-dark text-sm italic my-2" v-text="node.description"></p>
                                        
                                        <!-- <div class="w-full my-4 text-xs">
                                                <a :href="editCatURL(node.id)" class="no-underline text-purple-light hover:text-blue">Edit</a>
                                                &nbsp; | &nbsp;
                                                <a :href="addPageURL(node.id)" class="no-underline text-purple-light hover:text-blue">Add Page</a>
                                                &nbsp; | &nbsp;
                                                <a :href="addSubCatURL(node.id)" class="no-underline text-purple-light hover:text-blue">Add Sub-category</a>
                                        </div> -->
                                </div>
                                
                        </div>

                        <div v-if="node.children.length > 0">
                                <tree-view 
                                        :tree="node.children" 
                                        :listClass="listClass" 
                                        :itemClass="itemClass" 
                                        :level="level + 1" 
                                        :parentName="node.name"
                                ></tree-view>
                        </div>
                </li>
                
        </ul>
                
    </div>
</template>

<script>
    export default {

        
        props: {
                // an array containing elements to show as list
                "tree": Array, 

                // list of classes to be applied at all <ul> objects.
                "listClass": String,

                // classes to be applied on individual list item.
                "itemClass": String,

                // an indicator for level of depth. This helps determine 
                // the left alignment required for indentation.
                "level": {
                        default: 0,
                        type: Number
                },

                // name of the parent element.
                "parentName": {
                        default: '',
                        type: String
                }
        },

        methods: {
                editCatURL: function (id) {
                        return '/categories/' + id + '/edit'
                },
                addSubCatURL: function (id) {
                        return '/admin/categories/create?parent_id=' + id
                },
                addPageURL: function (id) {
                        return '/admin/pages/create?category_id=' + id
                }
        }
    }
</script>
