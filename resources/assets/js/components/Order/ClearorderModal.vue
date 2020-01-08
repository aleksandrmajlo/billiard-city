<template>
    <div class="modal fade" id="ClearorderModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <p class="text-center">{{text1}}</p>
                    <div class="flex">

                        <div class="item">
                            <a href="#" class="btn btn-warning" @click.prevent="Yes">{{text2}}</a>
                        </div>
                        <div class="item">
                            <a href="#" class="btn btn-warning" @click.prevent="Not">{{text3}}</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ClearorderModal",
        data(){
            return {
                text1:'',
                text2:'',
                text3:'',
            }
        },
        created(){
            this.text1=this.$store.state.lang.ClearorderModal[LanguneThisJs];
            this.text2=this.$store.state.lang.ClearorderModalYes[LanguneThisJs];
            this.text3=this.$store.state.lang.ClearorderModalNot[LanguneThisJs];
        },
        methods:{
            Yes(){
                 this.$store.commit('CartClear')
                 this.$store.commit('SetTotal')
                $('#ClearorderModal').modal('hide')
            },
            Not(){
                $('#ClearorderModal').modal('hide')
            }
        }
    }
</script>

<style scoped lang="scss">
    p{
        font-size: 16px;
        font-weight: bold;margin-bottom: 30px;
    }
    .flex{
         display: flex;
         justify-content: space-between;
         align-items: center;
         .item{
             width: 50%;
             text-align: center;
         }
    }
    .modal-body{
        padding-left: 0;
        padding-right: 0;
        padding-top: 20px;
        padding-bottom: 20px;
    }
</style>