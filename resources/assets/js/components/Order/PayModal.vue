<template>
    <div class="modal fade" id="PayModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">


            <div class="modal-content">
                <div class="modal-header">
                    <div class="title">{{$t('PayModal')}}</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <img src="/img/cll.png"/>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="type">

                        <div class="item " :class="{active:billing==1}" @click="setBilling(1)">
                            <img src="/images/nal.png"/>
                            <div>
                                  {{$t('PayModalNal')}}
                            </div>
                        </div>
                        <div class="item dis" :class="{active:billing==2}" @click="setBilling(2)">
                            <img src="/images/cart.png"/>
                            <div>
                                {{$t('CartModalPay')}}
                            </div>
                        </div>
                        <div class="item dis" :class="{active:billing==3}"  @click="setBilling(3)">
                            <img src="/images/sert.png"/>
                            <div>
                                {{$t('SertModalPay')}}
                            </div>
                        </div>

                    </div>
                    <div class="type">
                        <div class="itemComment">
                            <div class="comment">{{$t('PayModalComment')}}</div>
                        </div>
                    </div>
                    <div class="s-order">
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
                    </div>
                    <div class="print">
                        <div class="text">{{$t('PayModalPrint')}}</div>
                        <div class="switch-field">
                            <input type="radio" id="radio-one" name="switch-one" v-model="print" value="1" checked/>
                            <label for="radio-one">{{$t('ClearorderModalYes')}}</label>
                            <input type="radio" id="radio-two" name="switch-one" v-model="print" value="2"/>
                            <label for="radio-two">{{$t('ClearorderModalNot')}}</label>
                        </div>
                    </div>
                    <div class="text-center">
                        <button :disabled="disabled" @click.prevent="Pay" class="btn btn-warning ">
                            {{$t('PayModalPay')}}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "PayModal",
        props: ['order_id'],
        data() {
            return {
                disabled: false,
                print: 1,
                billing:1
            }
        },
        computed: {
            typeOrder() {
                return this.$store.state.typeOrder
            },
            price() {
                return this.$store.state.price
            },
            skidka() {
                return this.$store.state.skidka
            },
            total() {
                return this.$store.state.total
            },
        },
        methods: {
            Pay() {
                this.disabled = true;
                if (this.order_id == "table") {
                    this.$store.dispatch('PayTable', {
                        print: this.print,
                        billing:this.billing
                    })
                        .then(() => {
                            this.disabled = false;
                            this.$store.dispatch('getTables');
                            setTimeout(() => {
                                $('#PayModal').modal('hide');
                            }, 300)
                        });
                } else {
                    this.$store.dispatch('Pay', {
                        order_id: this.order_id,
                        billing:this.billing,
                        print: this.print})
                        .then(() => {
                            this.disabled = false;
                        });
                }

            },
            setBilling(billing){
                this.billing=billing;
            }
        }
    }
</script>
<style scoped>
    .item{
         cursor: pointer;
    }
</style>
