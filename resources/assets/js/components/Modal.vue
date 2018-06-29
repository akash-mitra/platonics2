<template>
        <div v-if="show" @click="closeModal" class="absolute pin-t pin-l h-screen w-screen" style="background-color: rgba(0,0,0,0.5)">
                <div class="container mx-auto h-full flex justify-center items-center">
                        <div :class="widthClass" @click.stop class="bg-white">

                                <div class="flex justify-between font-hairline p-6 border-b">
                                        <slot name="header"></slot>
                                        <span @click="closeModal" class="hover:text-grey-darker cursor-pointer">
                                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg>
                                        </span>
                                </div>
                                <div>
                                        <slot></slot>
                                </div>
                                <div class="p-6 border-t">
                                    <slot name="footer"></slot>
                                </div>
                        </div>
                </div>
        </div>
</template>

<script>
    export default {

        props: ['show', 'size'],

        computed: {

                widthClass: function () {
                        return 'sm:min-w-fill w-full lg:w-' + this.size
                }
        },

        created: function () {
                document.addEventListener("keydown", (e) => {
                        if (this.show && e.keyCode == 27) {
                                this.closeModal();
                        }
                });
        },

        methods: {
                closeModal: function () {
                        this.$emit('close')
                }
        }
    }
</script>
