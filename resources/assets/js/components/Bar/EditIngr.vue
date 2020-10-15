<template>
    <a href="#" @click.prevent="ShowModal">
        <i class="fa fa-pencil" aria-hidden="true"></i>
    </a>
</template>

<script>
    import {eventBus} from '~/app'
    export default {
        name: "EditIngr",
        props:['ingredient'],
        methods:{
            ShowModal(){
                eventBus.$emit('ShowFormIngr',{
                    newAdd:false,
                    ingredient:this.ingredient
                })

            }
        }
    }
</script>
