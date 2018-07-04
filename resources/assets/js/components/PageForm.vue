<template>
<div>
        <div class="md:flex">
                <div class="md:w-2/3 min-h-screen bg-white main-content">
                        
                        <div class="px-4 py-2 border-b">
                                <p class="text-xs text-grey-dark pb-2">
                                        Title
                                </p>

                                <input v-model="title" type="text" class="text-grey-darker text-2xl w-full"  :class="title.length == 0?'bg-grey-lightest':''" @keydown="errors.clear('title')">

                                <p class="text-xs text-right text-grey-dark my-1">
                                        
                                        <span v-if="title.length>80">
                                                Recommended: <i class="text-green">50 &dash; 80</i> chars | 
                                                Current: <i class="text-red" v-text="title.length"></i>.
                                        </span>

                                        <span class="text-red" v-if="errors.has('title')" v-text="errors.get('title')"></span>

                                        &nbsp;
                                </p>

                                
                                
                        </div>

                        <div class="px-4 py-2 border-b">
                                <p class="text-xs text-grey-dark pb-2">
                                        Summary
                                </p>
                                
                                <textarea v-model="summary" type="text" class="pb-2 italic text-grey-darkest font-light w-full border-b-q" :class="summary.length == 0?'bg-grey-lightest':''" @keydown="errors.clear('summary')"></textarea>

                                <p class="text-xs text-right text-grey-dark my-1">
                                        
                                        <span v-if="summary.length>210">
                                                Recommended: <i class="text-green">140 &dash; 210</i> chars | 
                                                Current: <i class="text-red" v-text="summary.length"></i>
                                        </span>
                                        <span class="text-red" v-if="errors.has('summary')" v-text="errors.get('summary')"></span>
                                        &nbsp;
                                </p>
                        </div>

                        <div class="px-4 py-2 border-b">
                                <p class="flex text-xs text-grey-darker pb-2">
                                        <span class="w-1/2">
                                                Body
                                        </span>
                                        <span class="w-1/2 text-right">
                                                <span v-text="lastWordCount"></span> words | <span v-text="readingTime"></span>
                                        </span>
                                </p>
                                
                                <div id="content-div" class="text-sm text-grey-darker py-2" style="min-height: 200px;">
                                        <medium-editor 
                                                :text='innerHTML' 
                                                :options='options' 
                                                custom-tag='div' 
                                                v-on:edit='applyTextEdit'
                                        ></medium-editor>
                                </div>

                                <span class="text-red text-sm" v-if="errors.has('body')" v-text="errors.get('body')"></span>
                                
                        </div>

                </div>

                <div class="w-full flex absolute pin-l pin-t md:mt-16 xl:mt-0 px-3 py-1">
                        <div class="w-1/6 bg-transparent hidden xl:block"></div>
                        <div class="w-5/6 flex bg-transparent">
                                <div class="w-2/3 flex flex-no-wrap bg-white py-2">
                                        <button 
                                                class="px-4 py-2 mx-1 text-grey-dark border rounded"
                                                title="Distraction free compose"
                                                >
                                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.8 15.8L0 13v7h7l-2.8-2.8 4.34-4.32-1.42-1.42L2.8 15.8zM17.2 4.2L20 7V0h-7l2.8 2.8-4.34 4.32 1.42 1.42L17.2 4.2zm-1.4 13L13 20h7v-7l-2.8 2.8-4.32-4.34-1.42 1.42 4.33 4.33zM4.2 2.8L7 0H0v7l2.8-2.8 4.32 4.34 1.42-1.42L4.2 2.8z"/></svg>
                                        </button>
                                        <button 
                                                v-show="title.length > 0 && innerText.length > 1" 
                                                @click="saveModalVisibility=true" 
                                                class="px-4 py-2 mx-1 text-grey-dark border hover:border-green hover:bg-green hover:text-white rounded"
                                                title="Save"
                                        >
                                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13 10v6H7v-6H2l8-8 8 8h-5zM0 18h20v2H0v-2z"/></svg>
                                        </button>

                                        <p class="px-4 py-3 italic text-grey-dark text-xs" v-text="serverMessages"></p>
                                </div>
                        </div>
                                
                </div>

                <div class="md:w-1/3 p-6 border-l">     

                        <div class="text-grey-dark text-sm">
                                <p class="py-2 font-normal text-teal-light">       
                                        <svg class="icon-sm text-teal-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 10a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"/></svg>
                                        Keyword Analyzer
                                </p>

                                <p class="mb-3 text-xs">Platonics will automagically identify keywords as you keep writing</p>
                                
                                <div class="flex flex-wrap w-full text-xs mb-4 pb-4 border-b">

                                        <div v-for="(keyword, index) in keywords" :key="index" class="flex m-1 b1order text-blue border-blue-light rounded bg-blue-lightest">
                                                <span class="p-1">
                                                        <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                                                </span>
                                                <span class="p-1" v-text="keyword"></span>  
                                        </div>

                                </div>
                        </div>

                        <div class="text-grey-dark text-sm">
                                <p class="py-2 font-normal text-teal-light">       
                                        <svg class="icon-sm text-teal-light" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 0a10 10 0 1 1 0 20 10 10 0 0 1 0-20zM2 10a8 8 0 1 0 16 0 8 8 0 0 0-16 0zm10.54.7L9 14.25l-1.41-1.41L10.4 10 7.6 7.17 9 5.76 13.24 10l-.7.7z"/></svg>
                                        Tags
                                </p>

                                <p class="mb-3 text-xs">Top keywords in the post. Click on them to add them as tags.</p>
                                
                                <div class="flex flex-wrap w-full text-xs mb-4 pb-4 border-b">

                                        <div class="flex m-1 b1order text-blue border-blue-light rounded bg-blue-lightest">
                                                <span class="p-1">
                                                        <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M11 9h4v2h-4v4H9v-4H5V9h4V5h2v4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                                                </span>
                                                <span class="p-1">Data Migration</span>  
                                        </div>

                                        <div class="flex m-1 b1order text-green border-green-light rounded bg-green-lightest">
                                                <span class="p-1">
                                                        <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM6.7 9.29L9 11.6l4.3-4.3 1.4 1.42L9 14.4l-3.7-3.7 1.4-1.42z"/></svg>
                                                </span>
                                                <span class="p-1">SQL</span>  
                                        </div>

                                </div>
                        </div>

                </div>
        </div>
        
        <modal size="4/5" :show="saveModalVisibility" v-on:close="toggleSaveModal">
                <template slot="header">
                        <h1 class="font-light">
                                Have a final look
                                <br>
                                <small class="font-normal text-sm">
                                        Make sure you fill-out all the information below
                                </small>
                        </h1>
                </template>

                <div class="w-full flex p-6 border-b">

                        <div class="w-1/2 px-4">

                                <div class="">

                                        <label class="block uppercase tracking-wide text-grey-darker text-xs mb-2" for="grid-state">
                                                Category
                                        </label>

                                        <div class="relative">
                                                <select v-model="category_id" @change="errors.clear('category_id')" class="block appearance-none w-full bg-grey-lightest border border-grey-lighter text-grey-darker py-2 px-4 pr-8 rounded leading-tight">
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

                                        <p class="text-xs text-red my-1" v-if="errors.has('category_id')" v-text="errors.get('category_id')"></p>
                                </div>

                                

                                <div class="pt-6">

                                        <label class="block uppercase tracking-wide text-grey-darker text-xs mb-2" for="grid-state">
                                                Access Level
                                        </label>

                                        <div class="relative">
                                                <select v-model="access_level" @change="errors.clear('access_level')" class="block appearance-none w-full bg-grey-lightest border border-grey-lighter text-grey-darker py-2 px-4 pr-8 rounded leading-tight">
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
                        </div>
                        <div class="w-1/2 px-4 border-l">
                                
                                <div>
                                        
                                        <label class="flex justify-between uppercase tracking-wide text-grey-darker text-xs mb-2" for="grid-state">
                                                <span>Meta Description</span>
                                                <span class="normal-case text-grey cursor-pointer" @click="metadesc=summary">Copy From Summary</span>
                                        </label>

                                        <textarea v-model="metadesc" name="metadesc" placeholder="e.g. Tell Search Engines about this article..." @keydown="errors.clear('metadesc')" class="block appearance-none w-full bg-grey-lightest border border-grey-lighter text-grey-darker py-2 px-4 pr-8 rounded leading-tight"></textarea>

                                        <p class="text-xs text-red my-1" v-if="errors.has('metadesc')" v-text="errors.get('metadesc')"></p>
                                </div>

                                <div class="pt-4">
                                        <label class="block uppercase tracking-wide text-grey-darker text-xs mb-2" for="grid-state">
                                                Meta Keywords
                                        </label>

                                        <div class="block appearance-none w-full leading-tight">
                                                <span class="text-teal text-xs py-2 pr-2 rounded">
                                                        keyword
                                                </span>
                                        </div>
                                </div>
                        </div>

                </div>

                <div class="w-full px-6">

                        <p class="block uppercase tracking-wide text-grey-darker text-xs m-4">
                                Search Engine Preview
                        </p>

                        <div style="font-family: arial,sans-serif; font-weight: normal" class="m-4">
                                <h3 style="color: #1a0dab; font-size: 18px; font-weight: normal" v-text="title">
                                        
                                </h3>
                                <div style="color: #006621; font-size: 14px" v-text="pageUrl">
                                        
                                </div>
                                <div  style="font-size: small; line-height: 1.4">
                                        <span style="color: #808080" v-text="se_date()"></span>
                                        <span style="color: #545454" v-text="metadesc"></span>
                                </div>
                        </div>
                </div>

                <template slot="footer">
                        <div class="flex justify-end">
                                
                                <button @click="saveAsFinal" class="bg-purple text-white py-2 px-4 rounded hover:bg-green">Looks Good</button>
                        </div>
                </template>
        </modal>
</div>
</template>

<script>

    import editor from 'vue2-medium-editor'
    import modal from './Modal.vue'

    export default {

        props: ['page_id'],

        data() {
                return {

                        /**
                         * Page model related fields
                         */
                        id: '',
                        title: '',
                        summary: '',
                        category_id: null,
                        innerHTML: '',
                        innerText: '',
                        metakey: '',
                        metadesc: '',
                        media_url: '',
                        access_level: 'F',
                        publish: 'N',
                        draft: 'Y',

                        /*
                         * Categories
                         */ 
                        categories: [],

                        /**
                         * Frontend Editor settings
                         */
                        options: {
                                toolbar: { 
                                        buttons: ['bold', 'italic', 'underline', 'strikethrough', 'anchor', 'h2', 'h3', 'quote'] 
                                }
                        },
                        errors: new Errors(),

                        /**
                         * Metadata about the Page 
                         */
                        lastWordCount: 0,
                        lastCharCount: 0,
                        readingTime: 0,
                        saveCharCount: 0,
                        keywords: [],
                        

                        /**
                         * Other variables to control various modals 
                         * and behaviors in the page
                         */
                        autoSaveTimer: null,
                        refreshTimer: null,
                        saveModalVisibility: false,
                        serverMessages: ''
                }
        },

        components: {
                'medium-editor': editor,
                'modal': modal
        },

        created() {

                /**
                 * For any page_id is supplied, the form is preloaded
                 * with the contents of the page.
                 */
                if (this.page_id !== '')
                {
                        axios.request({
                                'url': '/api/pages/' +  this.page_id,
                                'method': 'get'
                        })
                        .then(response => {

                                let data = response.data;

                                this.id = data.id;
                                this.title = data.title;
                                this.summary = data.summary;
                                this.category_id = data.category_id;
                                this.innerHTML = data.contents.body;
                                this.metakey = data.metakey;
                                this.metadesc = data.metadesc;
                                this.media_url = data.media_url;
                                this.access_level = data.access_level;
                                this.publish = data.publish;
                                this.draft = data.draft;
                        })
                        .catch(error => {
                                console.log(error)
                                serverMessages = 'Error: ' + error
                        })
                }

                this.autoSaveTimer = setInterval(this.autoSave, 30000)

                this.refreshTimer = setInterval(this.refreshMeta, 5000)

                this.loadAllCategories()

        },
        

        computed: {

                pageUrl: function () {
                        return 'https://www.example.com/' + this.title.replace(/\s+/g, '-').toLowerCase()
                },
        },
        

        methods: {
                
                /**
                 * This is the connecting method between vue and medium editor
                 */
                applyTextEdit: function (operation) {
                        
                        this.innerText = operation.api.origElements.innerText

                        this.innerHTML = operation.api.origElements.innerHTML
                        
                },


                /**
                 * This method is called regularly after every n-th 
                 * seconds to refresh various metadata information 
                 * about the article. Following are the list of 
                 * metadata info that this method can refresh.
                 *
                 * - Word Counts
                 * - Minutes of reading
                 * - Keywords
                 * 
                 * NOTE: 
                 * Even if this method is called after every "n"
                 * seconds, whether or not the information are 
                 * actually refreshed also depends on the 
                 * return of "shouldRefresh()" function.
                 */
                refreshMeta: function () {

                        if (this.shouldRefresh()) {

                                // we will refresh the meta information
                                // in a staggard manner, so that we do
                                // not generate all CPU load at a time
                                self = this

                                // first change the word count
                                this.lastWordCount = this.getWordCount()

                                // after half second change the reading time
                                setTimeout(() => {
                                        self.readingTime = self.getReadingTime()
                                }, 500);

                                // after about half more seconds, find the keywords
                                setTimeout(() => {
                                        self.keywords = self.getKeywords()
                                }, 1000);
                                
                        }
                },


                toggleSaveModal: function () {

                        this.saveModalVisibility = ! this.saveModalVisibility
                },

                /**
                 * AutoSave attempts to save the page after a specific interval
                 * provided both the following conditions are met:
                 * 
                 * (a) The current length of the content is not equal to the last saved
                 *     length of the content. That is, the content has actually 
                 *     undergone changes.
                 * 
                 * (b) The title of the page is defined
                 * 
                 */
                autoSave: function () {
                        
                        let l = this.innerText.length
                        
                        if (this.saveCharCount !== l) {

                                let data = {
                                        title: this.title,
                                        summary: this.summary,
                                        body: this.innerHTML,
                                        draft: 'Y'
                                }

                                this.savePage (data)

                                this.saveCharCount = l
                        }
                },


                /**
                 * SaveAsFinal removes the draft flag and saves 
                 * the document with all the other required parameters 
                 * (such as category_id, access_level etc. which were not required 
                 * for saving as a draft).
                 * 
                 */
                saveAsFinal: function () {
                        
                        let data = {
                                title: this.title,
                                summary: this.summary,
                                body: this.innerHTML,
                                category_id: this.category_id,
                                metakey: this.metakey,
                                metadesc: this.metadesc,
                                publish: this.publish,
                                media_url: this.media_url,
                                access_level: this.access_level,
                                draft: 'N'
                        }

                        this.savePage (data)

                        this.saveCharCount = this.innerText.length
                        
                },


                /**
                 * shouldRefresh function slows down the reactivity in order to
                 * make the frontend feel more responsive with large contents.
                 * 
                 * The current implementation of the function is based on count
                 * of characters present in the innerText. The function only
                 * returns true as and when character count increases by 10
                 * percent or decreases by 5 percent.
                 * 
                 */
                shouldRefresh: function () {

                        let currentCharCount = this.innerText.length

                        if (
                                (currentCharCount > this.lastCharCount && currentCharCount > 1.1 * this.lastCharCount)
                                ||
                                (currentCharCount < this.lastCharCount && currentCharCount < 1.05 * this.lastCharCount)
                         ) {

                                this.lastCharCount = currentCharCount

                                return true
                        }

                        return false
                },


                /**
                 * saves the page to the server with the given data
                 */
                savePage: function (data) {

                        this.serverMessages = 'Saving document to server...'

                        axios.request({
                                'url': '/admin/pages/' +  this.id,
                                'method': this.id > 0 ? 'patch' : 'post',
                                'data': data
                        })
                        .then(response => {

                                this.id = response.data.id

                                this.saveModalVisibility = false

                                let self = this

                                setTimeout (() => { self.serverMessages = '' }, 2000);

                        })
                        .catch(error => {

                                this.saveCharCount = 0

                                this.errors.record(error.response.data.errors)

                                console.log(error)
                        })
                },


                /**
                 * Returns current date in MMM DD, YYYY format as
                 * used in Search Engine search results.
                 */
                se_date: function () {
                        let month_names = ["Jan", "Feb", "Mar", 
                                        "Apr", "May", "Jun", "Jul", "Aug", "Sep", 
                                        "Oct", "Nov", "Dec"]
                        
                        let today = new Date()
                        let day = today.getDate()
                        let month_index = today.getMonth()
                        let year = today.getFullYear()

                        return month_names[month_index] + ' ' + day + ", " + year + ' - '
                },


                /*
                 * Populate the available categories for category selection dropdown
                 */
                loadAllCategories: function () {

                        axios.get('/api/categories')
                        .then(response => {
                                this.categories = response.data.data
                        }).catch(error => {
                                serverMessages = 'Failed to load categories'
                                console.log(error)
                        })
                },

                /**
                 * Keyword frequency analyser
                 */
                getKeywords: function () {

                        let txt = this.innerText

                        let singleWordTag = {}, doubleWordTag = {}
                        let l = txt.length, previousCharWasSeperator = false
                        let c = '', word = '', previousWord = ''
                        let ignoreWords = {
                                'a': true, 'an': true, 'the': true, 'in': true, 'into': true, 'of': true, 'on': true, 'onto': true, 
                                'between': true, 'and': true, 'to': true, 'at': true, 'if': true, 'but': true, 'this': true, 'that': true, 
                                'what': true, 'who': true, 'why': true, 'how': true, 'when': true, 'where': true, 'there': true, 'here': true, 
                                'is': true, 'am': true, 'was': true, 'were': true, 'are': true, 'will': true, 'may': true, 'might': true, 
                                'should': true, 'would': true, 'than': true, 'by': true, 'it': true, 'I': true, 'you': true, 'we': true, 'he':true, 
                                'she':true, 'her':true, 'his':true, 'us': true, 'be':true, 'too':true, 'with':true, 'hence':true, 'thus':true, 'let':true, 
                                'whether':true, 'there': true, 'they':true, 'as':true, 'any':true, 'their':true, 'all':true, 'has':true, 'have':true, 
                                'had':true, 'either':true, 'having':true, 'up':true, 'down':true, 'must': true, 'for': true, 'i': true
                        }

                        for (let i = 0; i < l; i++)
                        {
                                c = txt.charAt(i).toLowerCase() // all letters are changed to lower case
                                
                                if (c === ' ' || c === '.' || c === ',' || c === '!' || c === ';' || c === '-' || c === '—')  // all separators 
                                {   
                                        /*
                                        * This code ensures that consecutive punctuations or
                                        * punctuation with space are ignored
                                        */
                                        if (previousCharWasSeperator) {
                                                continue
                                        }

                                        /*
                                        * If the word we built is one of the ignorable words
                                        * we continue the loop without storing the word
                                        */
                                        if (word in ignoreWords) {
                                                word = ''
                                                previousWord = ''
                                                continue
                                        }
                                
                                        /*
                                        * Here we build double word tags by concatenating
                                        * the current word with the previous word
                                        */
                                        if (previousWord != '') {
                                                let twoWords = previousWord + ' ' + word

                                                /*
                                                * We store the double word tags and their frequency here
                                                */
                                                if (doubleWordTag.hasOwnProperty(twoWords)) {
                                                        doubleWordTag[twoWords] = doubleWordTag[twoWords] + 1;
                                                } else {
                                                        doubleWordTag[twoWords] = 1;
                                                }
                                        }

                                        /*
                                        * This code make sure that we do not make a double word
                                        * tag where there is a punctuation in between those words
                                        */
                                        if (c === '.' || c === ',' || c === '!' || c === ';') {
                                                previousWord = ''
                                        }
                                        else {
                                                previousWord = word
                                        }


                                        /*
                                        * We store the single word tags and their frequency here
                                        */
                                        if (singleWordTag.hasOwnProperty(word)) {
                                                singleWordTag[word] = singleWordTag[word] + 1;
                                        } 
                                        else {
                                                singleWordTag[word] = 1;
                                        }
                                
                                        /* empty out the variables */
                                        word = ''
                                        previousCharWasSeperator = true
                                }
                                else {
                                        /*
                                        * This is where we build the words from the 
                                        * individual characters (except punctuation chars)
                                        */
                                        word += c
                                        previousCharWasSeperator = false
                                }
                                
                        }
                        let singleTags = Object.keys(singleWordTag).sort(function (a, b) { return singleWordTag[a] < singleWordTag[b] }).slice(0, 5)
                        let doubleTags = Object.keys(doubleWordTag).sort(function (a, b) { return doubleWordTag[a] < doubleWordTag[b] }).slice(0, 5)

                        return doubleTags.concat(singleTags)

                }, // end of keyword

                getWordCount: function () {
                        
                        return this.innerText.split(' ').length
                },

                getReadingTime: function () {
                        
                        let min = Math.round (this.lastWordCount / 190)
                        
                        if (min < 1) return 'Under a minute to read'
                        
                        if (min <= 7) return 'Nice size. About ' + min + ' min to read'
                        
                        if (min <= 9) return 'Post is getting big. About ' + min + ' min to read'
                        
                        return 'Post too big. More than ' + min + ' min to read'
                        
                }

        } // end of methods
        
}
</script>
