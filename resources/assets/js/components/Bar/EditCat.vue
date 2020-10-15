<template>
    <a href="#" @click.prevent="ShowModal">
        <i class="fa fa-pencil" aria-hidden="true"></i>
    </a>
</template>

<script>
    import {eventBus} from '~/app'
    export default {
        name: "EditCat",
        props:['category'],
        methods:{
            ShowModal(){
                eventBus.$emit('ShowFormCat',{
                    newAdd:false,
                    category:this.category
                })

            }
        }
    }
</script>

