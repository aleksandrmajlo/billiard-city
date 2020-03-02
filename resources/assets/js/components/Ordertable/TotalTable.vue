<template>
    <div class="total-order">

        <table class="table order-final-priсe" align="center">
            <tbody>
            <tr>
                <td>{{text2}}</td>
                <td>{{price}} ₴</td>
                <td>{{text4}}</td>
            </tr>
            <tr>
                <td>{{text3}}</td>
                <td>{{skidka}}%</td>
                <td class="total-price">{{total}} ₴</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "TotalTable",
        props: ['order_id','total_first','price_first'],
        data(){
            return {
                text2:'',
                text3: '',
                text4: '',
            }
        },
        computed: {

            price(){
                return this.$store.state.price
            },
            skidka(){
                return this.$store.state.skidka
            },
            total(){
                return this.$store.state.total
            },

        },

        created(){
            this.$store.dispatch('getOrder',this.order_id);

            // this.$store.dispatch('getTablePrice',this.order_id);
            this.text2 = this.$store.state.lang.PayModalPrice[LanguneThisJs];
            this.text3 = this.$store.state.lang.PayModalDiscount[LanguneThisJs];
            this.text4 = this.$store.state.lang.PayModalTotal[LanguneThisJs];
        },
        mounted(){

            this.$store.commit('SetTotalTable',{
                priceOrder:this.price_first,
                priceOrderTotal:this.total_first
            })
            $('.s-order').removeClass('hidden');
        }

    }
</script>

<style scoped>

</style>