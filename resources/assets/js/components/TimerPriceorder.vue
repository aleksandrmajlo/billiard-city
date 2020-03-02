<template>
    <div class="timerPriceorderConteer text-center">
        <div class="priceBlock">
            <span class="price">{{priceOrderTotal}}</span>
            <span class="valut">₴</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TimerPriceorder",
        data() {
            return {
                priceOrder: 0,
                priceOrderTotal: 0,
                priceOrderDiscount: 0,
                first: false
            }
        },
        props: {
            order_id: String,
            start: String,

        },
        created() {
            setTimeout(() => {
                this.getPrice()
            }, parseInt(this.start) * 2000)
        },
        methods: {
            getPrice() {
                Vue.axios.get('/ajax/priceorder' + '?order_id=' + this.order_id)
                    .then((response) => {

                        this.priceOrder = response.data.results.priceOrder;
                        this.priceOrderTotal = Math.ceil((response.data.results.priceOrderTotal) * 100) / 100;
                        this.priceOrderDiscount = Math.ceil((response.data.results.priceOrderDiscount) * 100) / 100;

                        if (!this.first) {
                            this.first = true;
                            setInterval(() => {
                                this.getPrice();
                            }, 60000)
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }

    }
</script>

<style scoped>
    .price {
        display: inline-block;
        margin-right: 5px;
    }
</style>