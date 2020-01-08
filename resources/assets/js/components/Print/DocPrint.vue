<template>
    <a @click.prevent="send"  class="btn btn-info">Друк </a>
</template>

<script>
    import print from 'print-js'
    export default {
        name: "DocPrint",
        props:['id','header'],
        methods:{
            send(){
                printJS({ printable: this.id, type: 'html', header: this.header })
            }
        }
    }
</script>
