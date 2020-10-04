<template>
    <button @click.prevent="send"  class="worker__button worker__print" v-cloak>
        <img src="/img/print.png" alt="colendar"><span>{{$t('print')}}</span>
    </button>
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
