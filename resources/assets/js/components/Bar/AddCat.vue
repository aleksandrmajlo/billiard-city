<template>
    <a class="edit " @click.prevent="addNew" href="#">{{$t('Add')}}</a>
</template>

<script>
    import {eventBus} from '~/app'
    export default {
        name: "AddCat",
        methods:{
            addNew(){
                eventBus.$emit('ShowFormCat',{
                    newAdd:true
                })
            }
        }
    }
</script>

<style scoped>

</style>