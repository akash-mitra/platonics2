<template>
        <div>
                <h3 class="p-4 border-b">Comments</h3>

                <div v-for="(comment, index) in comments" :key="index" class="border-b p-8">

                        <div class="flex w-full ">
                                <img class="w-6 h-6 border border-white rounded-full mr-4" src="https://pbs.twimg.com/profile_images/885868801232961537/b1F6H4KC_400x400.jpg" alt="Avatar of User">
                                <p class="text-grey-dark leading-none">User name </p>
                                <p class="text-grey leading-none"> &nbsp;Commented </p>
                        </div>

                        <p class="pt-4" v-text="comment.body"></p>
                        
                </div>

                <div class="w-full p-8">
                        <h4 class="text-grey pb-4">Have your say...</h4>
                        <textarea class="w-full h-16 bg-grey-lighter border rounded"></textarea>
                </div>
        </div>

</template>

<script>

    export default {

        props: ['commentable', 'commentableid'],
        
        data() {
            
            return {
                comments: []

            }
        },

        computed: {

                
        },
        
        created() {

            axios.get('/api/comments/' + this.commentable + '/' + this.commentableid)

                .then(response => {
                    
                    this.comments = response.data.data;
                    console.log(this.comments)
                    
                })
        },

        methods: {
        
                
        }
        
    }
</script>
