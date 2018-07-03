@extends('layouts.app')

@section('page.css')
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet"> -->


        <link rel="stylesheet" href="{{ mix('/css/medium-editor.css') }}">
        

        <style>

                .icon {
                        display: inline-block;
                        height: 1.15em;
                        width: 1.25em;
                        fill: currentColor;
                        vertical-align: middle;
                }


        </style>
@endsection

@section('header')

        <div class="my-3 px-6">
                
                <input type="text" placeholder="search..." class="p-2">
                
        </div>
@endsection

@section('content')
    
    <div id="app" class="w-full bg-white">

        <div v-if="message.length > 0" :class="messageClass" class="flex justify-between p-8 -mt-1 border-t border-b">
                <span v-text="message"></span>
                <span @click="dismissMessage"><svg class="icon cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg></span>
        </div>
            
        <h1 class="px-4 sm:px-8 py-8 font-hairline">
                        <span v-text="heading"></span> 

                        <button @click="saveConfig" class="text-sm float-right bg-purple text-white pqy-1 py-2 px-8 rounded hover:bg-green">
                                Save
                        </button>
        </h1>

        <div class="w-full flex border-t border-b bg-white">
                
                <div class="w-1/2 p-4 bg-grey-lighter border-r">

                        <div class="relative">
                                <select v-model="selectedTemplate" class="w-full appearance-none bg-white rounded-none py-3 px-4 pr-8 leading-tight shadow-md">
                                        <option disabled>Please select one</option>
                                        <option value="home">Home Page</option>
                                        <option value="pages">Single Article Page</option>
                                        <option value="category">Category Page</option>
                                        <option value="profile">User Profile Page</option>
                                        <option value="forumhome">Forum Home Page</option>
                                        <option value="forum">Single Forum Thread Page</option>
                                </select>
                                <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-grey-darker">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                                
                        </div>
                        <div class="px-8 py-4">
                                <label class="text-grey-dark font-semibold">
                                        <input v-model="templates[selectedTemplate].header.display" class="mr-2 leading-tight" type="checkbox">
                                        <span class="text-sm">
                                                Header
                                        </span>
                                </label>
                                <input v-if="templates[selectedTemplate].header.display" type="text" v-model="templates[selectedTemplate].header.class" class="bg-grey-lightest w-full my-2 text-sm p-2 border" placeholder="Optional Header Classes">
                        </div>

                        <div class="px-8 py-2">
                                <label class="text-grey-dark font-semibold">
                                        <input v-model="templates[selectedTemplate].subheader.display" class="mr-2 leading-tight" type="checkbox">
                                        <span class="text-sm">
                                                Sub-Header
                                        </span>
                                </label>
                                <input v-if="templates[selectedTemplate].subheader.display" type="text" v-model="templates[selectedTemplate].subheader.class" class="bg-grey-lightest w-full my-2 p-2 text-sm border" placeholder="Optional Sub-Header Classes">
                        </div>

                        <div class="px-8 py-2">
                                <label class="text-grey-dark font-semibold">
                                        <input v-model="templates[selectedTemplate].left.display" class="mr-2 leading-tight" type="checkbox">
                                        <span class="text-sm">
                                                Left
                                        </span>
                                </label>
                                <input v-if="templates[selectedTemplate].left.display" type="text" v-model="templates[selectedTemplate].left.class" class="bg-grey-lightest w-full my-2 text-sm p-2 border" placeholder="Optional Left Classes">
                        </div>

                        <div class="px-8 py-2">
                                <label class="text-grey-dark font-semibold">
                                        <input v-model="templates[selectedTemplate].center.display" class="mr-2 leading-tight" type="checkbox">
                                        <span class="text-sm">
                                                Center
                                        </span>
                                </label>
                                <input v-if="templates[selectedTemplate].center.display" type="text" v-model="templates[selectedTemplate].center.class" class="bg-grey-lightest w-full my-2 text-sm p-2 border" placeholder="Optional Center Classes">
                        </div>

                        <div class="px-8 py-2">
                                <label class="text-grey-dark font-semibold">
                                        <input v-model="templates[selectedTemplate].right.display" class="mr-2 leading-tight" type="checkbox">
                                        <span class="text-sm">
                                                Right
                                        </span>
                                </label>
                                <input v-if="templates[selectedTemplate].right.display" type="text" v-model="templates[selectedTemplate].right.class" class="bg-grey-lightest w-full my-2 text-sm p-2 border" placeholder="Optional Right Classes">
                        </div>

                        <div class="px-8 py-2">
                                <label class="text-grey-dark font-semibold">
                                        <input v-model="templates[selectedTemplate].bottom.display" class="mr-2 leading-tight" type="checkbox">
                                        <span class="text-sm">
                                                Bottom
                                        </span>
                                </label>
                                <input v-if="templates[selectedTemplate].bottom.display" type="text" v-model="templates[selectedTemplate].bottom.class" class="bg-grey-lightest w-full my-2 text-sm p-2 border" placeholder="Optional Bottom Classes">
                        </div>

                        <div class="px-8 py-2">
                                <label class="text-grey-dark font-semibold">
                                        <input v-model="templates[selectedTemplate].footer.display" class="mr-2 leading-tight" type="checkbox">
                                        <span class="text-sm">
                                                Footer
                                        </span>
                                </label>
                                <input v-if="templates[selectedTemplate].footer.display" type="text" v-model="templates[selectedTemplate].footer.class" class="bg-grey-lightest w-full my-2 text-sm p-2 border" placeholder="Optional Footer Classes">
                        </div>
                        
                </div>
                <div class="w-1/2 flwex justify-center p-4 bg-grey-lighter">
                        <div class="w-full shadow-md bg-white border">
                                <div v-if="templates[selectedTemplate].header.display" :class="templates[selectedTemplate].header.class" class="border h-16 p-4 text-center">Header</div>
                                <div v-if="templates[selectedTemplate].subheader.display" :class="templates[selectedTemplate].subheader.class" class="border h-16 p-4 text-center">Sub-Header</div>
                                <div class="container mx-auto w-full flex justify-center border h-48 text-center">
                                        <div v-if="templates[selectedTemplate].left.display" :class="templates[selectedTemplate].left.class" class="border">Left</div>
                                        <div v-if="templates[selectedTemplate].center.display" :class="templates[selectedTemplate].center.class" class="border">Center</div>
                                        <div v-if="templates[selectedTemplate].right.display" :class="templates[selectedTemplate].right.class" class="border">Right</div>
                                </div>
                                <div v-if="templates[selectedTemplate].bottom.display" :class="templates[selectedTemplate].bottom.class" class="border h-16 p-4 text-center">Bottom</div>
                                <div v-if="templates[selectedTemplate].footer.display" :class="templates[selectedTemplate].footer.class" class="border h-16 p-4 text-center">Footer</div>
                        </div>
                </div>

        </div>
            
    </div>

@endsection

@section('page.script') 

<script>
        new Vue ({
                el: '#app',
                data: {
                        templates: {
                                'home': {body: {class: ''}, header: {display: false, class: ''}, subheader: {display: false, class: ''}, left: {display: false, class: ''}, center: {display: false, class: ''}, right: {display: false, class: ''}, bottom: {display: false, class: ''}, footer: {display: false, class: ''} },
                                'pages': {body: {class: ''}, header: {display: false, class: ''}, subheader: {display: false, class: ''}, left: {display: false, class: ''}, center: {display: false, class: ''}, right: {display: false, class: ''}, bottom: {display: false, class: ''}, footer: {display: false, class: ''} },
                                'category': {body: {class: ''}, header: {display: false, class: ''}, subheader: {display: false, class: ''}, left: {display: false, class: ''}, center: {display: false, class: ''}, right: {display: false, class: ''}, bottom: {display: false, class: ''}, footer: {display: false, class: ''} },
                                'profile': {body: {class: ''}, header: {display: false, class: ''}, subheader: {display: false, class: ''}, left: {display: false, class: ''}, center: {display: false, class: ''}, right: {display: false, class: ''}, bottom: {display: false, class: ''}, footer: {display: false, class: ''} },
                                'forum': {body: {class: ''}, header: {display: false, class: ''}, subheader: {display: false, class: ''}, left: {display: false, class: ''}, center: {display: false, class: ''}, right: {display: false, class: ''}, bottom: {display: false, class: ''}, footer: {display: false, class: ''} },
                                'forumhome': {body: {class: ''}, header: {display: false, class: ''}, subheader: {display: false, class: ''}, left: {display: false, class: ''}, center: {display: false, class: ''}, right: {display: false, class: ''}, bottom: {display: false, class: ''}, footer: {display: false, class: ''} }
                        },
                        selectedTemplate: 'home',

                        // other frontend side variables
                        heading: 'Templates',
                        message: '',
                        messageClass: ''
                },

                created: function () {
                        this.heading = 'Loading Templates. Please wait...'
                        axios.request({
                                url: "{{ route('configurations.show', 'templates') }}",
                                method: 'get'
                        })
                        .then(response => { 
                                this.templates = response.data.templates 
                                this.heading = 'Templates'
                        })
                        .catch(error => {
                                this.heading = 'Error Loading Templates'
                                this.message = 'Error Loading templates: ' + error
                                this.messageClass = 'bg-red-lightest text-red-dark border-red'
                        })
                },
                
                methods: {
                        saveConfig: function () {

                                axios.request({
                                        url: "{{ route('configurations.store') }}",
                                        method: 'post',
                                        data: {
                                                key: "templates",
                                                value: this.templates
                                        }
                                })
                                .then(response => {
                                        this.message = 'Successfully saved the Template Configurations'
                                        this.messageClass = 'bg-green-lightest text-green-dark border-green'
                                })
                                .catch(error => {
                                        this.message = 'Error saving template config: ' + error
                                        this.messageClass = 'bg-red-lightest text-red-dark border-red'
                                })
                        },

                        dismissMessage: function () {
                                this.message = ''
                        }
                }
        })
</script>
        
@endsection
