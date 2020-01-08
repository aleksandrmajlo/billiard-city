<template>
    <div class="to-pay clearfix">

        <div class="leftItem">
            <v-popover
                    placement="top-start"
                    offset="4"
            >
                <button class="pay-check" type="button"><img src="/images/pay-button-1.png"></button>
                <template slot="popover">
                    <a v-close-popover="true" href="#" @click.prevent="ClearOrder">Очистить заказ</a>
                </template>
            </v-popover>
            <button class="pay-check" type="button" @click.prevent="SmsModal"><img src="/images/pay-button-2.png">
            </button>
            <button :disabled="disabledPrint" class="pay-check" type="button" @click.prevent="PrintOrder"><img src="/images/pay-button-3.png"></button>

        </div>

        <div class="rigchtItem">
            <button class="btn btn-warning pull-right" @click.prevent="PayModal" type="button">{{text1}}</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ButtonBlock",
        props:['order_id'],
        data() {
            return {
                text1: '',
                disabledPrint:false
            }
        },
        created() {
            this.text1 = this.$store.state.lang.PayModalPay[LanguneThisJs];
        },
        methods: {
            ClearOrder() {
                $('#ClearorderModal').modal('show')
            },
            PayModal() {
                $('#PayModal').modal('show');
            },
            SmsModal() {
                $('#SmsModal').modal('show');
            },
            PrintOrder(){
                this.disabledPrint=true;
                axios.post('/order/OrderPrint', {
                    order_id: this.order_id,
                })
                    .then( (response)=> {
                        console.log(response);
                    })
                    .catch((error)=> {
                        // handle error
                        console.log(error);
                    })
                    .finally(()=> {
                        this.disabledPrint=false;
                    });
            }
        }
    }
</script>
<style scoped lang="scss">
    .to-pay {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        width: 100%;
        .leftItem {
            display: flex;
            flex-wrap: wrap;
            button {
                margin-bottom: 0;
            }
        }
    }

    @media screen and (max-width: 480px) {
        .to-pay {
            margin: 0;
            .leftItem {
                justify-content: center;
                width: 100%;
                margin-bottom: 25px;
            }
            .rigchtItem {
                justify-content: center;
                width: 100%;
            }
        }
    }
</style>