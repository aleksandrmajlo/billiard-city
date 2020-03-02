<template>
    <div class="row">
        <div  v-for="(table,index) in tables" v-if="table.free"
              :class="{ 'active' : table.id == TableAddActive}"
              class="col-xs-4 board_col">
            <a href="#" @click.prevent="OpenOrder(table.id)" class="board_imem item-close">
                <img :src="table.image" alt="">
                <p>{{table.name}}</p>
                <span>{{table.number}}</span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "FreeTable",
        computed: {
            tables() {
                return this.$store.state.tables
            },
            TableAddActive(){
                return this.$store.state.TableAddActive;
            }
        },
        methods:{
            OpenOrder(id){
                $('.ConteerRowTable').removeClass('TableOpen');
                $('.ConteerRowTable').addClass('TableOpenFree');
                this.$store.commit('SetTableAddActive', id)
            }
        }
    }
</script>

<style scoped>

</style>