<template>
    <div class=" searcBlock"  v-show="ShowHideSearch" :style="'right:'+right+'px'">
        <input placeholder="мінімум 3 символи" v-model="search"  :style="'min-height:'+height+'px;'" class="form-control" >
    </div>
</template>
<script>
    export default {
        name: "Search",
        data(){
            return {
                ShowHideSearch:false,
                search:'',
                right:0,
                height:0
            }
        },
        mounted() {
            this.$root.$on("ShowHideSearch", ob => {
                this.res();
                this.ShowHideSearch=!this.ShowHideSearch
            });
            window.addEventListener('resize', this.res);
        },
        watch: {
            search(val,oldVal){
                if(val.length>=3){
                    this.$store.dispatch('SearchProduts',val)
                }

            }
        },
        methods:{
            res(){
                this.right=$('#SearchButton').outerWidth()+15;
                this.height=$('#SearchButton').outerHeight();
            }
        }
    }
</script>

<style scoped lang="scss">
    .searcBlock{
        position: absolute;
        top:0;
    }
    .mb10{
       margin-bottom: 10px;
    }
</style>