<template>
   <div></div>
</template>

 <script>

  import vueMediumEditor from 'vue2-medium-editor'


  export default {    

    props: ['props', 'options'],
    methods: {
        processEditOperation: function (operation) {
                this.text = operation.api.origElements.innerHTML
        }
    }

  }
 </script>