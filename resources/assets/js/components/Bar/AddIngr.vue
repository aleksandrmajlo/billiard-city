<template>
    <a class="edit " @click.prevent="addNew" href="#">{{$t('Add')}}</a>
</template>

<script>
    import {eventBus} from '~/app'
    export default {
        name: "AddIngr",
        methods:{
            addNew(){
                eventBus.$emit('ShowFormIngr',{
                    newAdd:true
                })
            }
        }
    }
</script>
