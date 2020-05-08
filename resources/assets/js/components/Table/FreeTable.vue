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
        data(){
            return {
                openChangeId:false,
                isAdmin:false
            }
        },
        created(){
            axios.post('/table/openChangeId').then(response => {
                this.openChangeId=response.data.openChangeId;
                this.isAdmin=response.data.isAdmin;
                if(!this.openChangeId&&!this.isAdmin){
                    this.$swal.fire({
                        icon: 'error',
                        text: 'Необхідно відкрити зміну',
                        showConfirmButton: false,
                    });
                }
            })
        },
        computed: {
            tables() {
                return this.$store.state.tables
            },
            TableAddActive(){
                return this.$store.state.TableAddActive;
            }
        },
        watch:{
                tables(){
                    let count=0;
                    this.tables.forEach(el=>{
                        if(el.free)count++
                    });
                    if(count>0){
                        $('#freetableTable').removeClass('hidden');
                    }else{
                        $('#freetableTable').addClass('hidden');
                    }
                }
        },
        methods:{
            OpenOrder(id){
                if(this.openChangeId||this.isAdmin){
                    $('.ConteerRowTable').removeClass('TableOpen');
                    $('.ConteerRowTable').addClass('TableOpenFree');
                    this.$store.commit('SetTableAddActive', id);
                    this.$root.$emit("SetClientNull");
                }
            }
        }
    }
</script>

<style scoped>

</style>