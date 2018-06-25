import editor from 'vue2-medium-editor'

new Vue ({
        
        el: '#app',

        data() {
                return {
                        id:'',
                        title: '',
                        summary: '',
                        innerHTML: '',
                        innerText: '',
                        options: {
                                toolbar: { 
                                        buttons: ['bold', 'italic', 'underline', 'strikethrough', 'anchor', 'h2', 'h3', 'quote'] 
                                }
                        },
                        lastWordCount: 0,
                        lastCharCount: 0,
                        errors: new Errors()
                }
        },



        components: {
                'medium-editor': editor
        },
        

        computed: {

                totalWords: function () {
                        
                        if (this.shouldRefresh()) {
                                
                                this.lastWordCount = this.innerText.split(' ').length

                                return this.lastWordCount
                        }

                        return this.lastWordCount
                },

                readingTime: function () {
                        
                        let min = Math.round (this.totalWords / 190)
                        
                        if (min < 1) return 'Under a minute to read'
                        
                        if (min <= 7) return 'Nice size. About ' + min + ' min to read'
                        
                        if (min <= 9) return 'Post is getting big. About ' + min + ' min to read'
                        
                        return 'Post too big. More than ' + min + ' min to read'
                        
                }
        },

        methods: {
                
                applyTextEdit: function (operation) {
                        
                        this.innerText = operation.api.origElements.innerText

                        this.innerHTML = operation.api.origElements.innerHTML
                        
                },


                /**
                 * shouldRefresh function slows down the reactivity in order to
                 * make the front end feel more responsive.
                 * 
                 * The current implementation of the function is based on count
                 * of characters present in the innerText. The function only
                 * returns true as and when character count increases by 10
                 * percent. 
                 * 
                 * Alternate approach could be based on random numbers if
                 * divisible by a specific number etc. Implementation 
                 * has to be very simple and faster as the function 
                 * is called on every tick.
                 */
                shouldRefresh: function () {

                        let currentCharCount = this.innerText.length

                        if (
                                (currentCharCount > this.lastCharCount && currentCharCount > 1.1 * this.lastCharCount)
                                ||
                                (currentCharCount < this.lastCharCount)
                         ) {

                                this.lastCharCount = currentCharCount

                                return true
                        }

                        return false
                },

                savePage: function () {

                        

                        axios.request({
                                'url': '/pages/' +  this.$data.id,
                                'method': this.id > 0 ? 'patch' : 'post',
                                'data': {
                                        title: this.title,
                                        summary: this.summary,
                                        body: this.innerHTML
                                }
                        })
                        .then(response => {

                                this.id = response.data.id

                                //location.href = '/admin/categories'
                        })
                        .catch(error => {

                                this.errors.record(error.response.data.errors)

                                console.log(this.errors.get('title'))

                        })
                }
        }
})