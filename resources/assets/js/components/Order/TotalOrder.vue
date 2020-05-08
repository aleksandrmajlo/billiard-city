<template>
    <div class="total-order">
        <table class="table order-final-priсe" align="center">
            <tbody>
            <tr>
                <td>{{$t('PayModalPrice')}}</td>
                <td>{{price}} ₴</td>
                <td>{{$t('PayModalTotal')}}</td>
            </tr>
            <tr>
                <td>{{$t('PayModalDiscount')}}</td>
                <td>{{skidka}}%</td>
                <td class="total-price">{{total}} ₴</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "Total",
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
    }
</script>

<style scoped>
   .total-order{
        border:none;
   }
</style>