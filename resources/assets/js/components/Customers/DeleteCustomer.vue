<template>
    <a class="dell"  href="" @click.prevent="del" >{{$t('customersDelete')}}</a>
</template>

<script>
    export default {
        name: "DeleteCustomer",
        props: ['id'],
        methods:{
            del(){
                axios.delete('/customers/' + this.id, {
                    id:this.id,
                    }
                ).then(response=>{
                    this.showShwal('success', this.$t('customersDeleteText'));
                    setTimeout(()=>{
                        location.href='/customers';
                    },2000)
                }).catch(error => {
                    this.showShwal('error', this.$t('error'));
                }).then(() => {
                })
            }
        }
    }
</script>

<style scoped>

</style>