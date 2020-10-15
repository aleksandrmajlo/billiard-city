<template>
    <form id="searchform" v-on:submit.prevent="submit">
        <input  type="text" placeholder='' v-model="q">
        <button type="submit" >
            <img src="/img/search.png" alt="search">
        </button>
        <span v-show="isSearch" @click.prevent="cancel" style="color:blue;cursor:pointer;">{{$t('cancel')}}</span>
    </form>
</template>

<script>
    import {eventBus} from '~/app'
    export default {
        name: "SearchCat",
        data(){
            return{
                q:'',
                isSearch:false,
            }
        },
        methods:{
            submit(){
                this.isSearch=true;
                eventBus.$emit('searchCats', {
                    q: this.q,
                })
            },
            cancel(){
                this.q="";
                eventBus.$emit('searchCats', {
                    q: this.q,
                });
                this.isSearch=false;
            }
        }
    }
</script>
