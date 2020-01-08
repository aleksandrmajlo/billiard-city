<template>
    <div class="modal fade" id="PayModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="title">{{text1}}</div>
                    <div class="type">
                        <div class="item">
                            <img src="/images/nal.png"/>
                            <div>{{text5}}</div>
                        </div>
                    </div>
                    <div class="s-order">
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
                    </div>
                    <div class="print">
                        <div class="text">{{text6}}</div>
                        <div class="switch-field">

                            <input type="radio" id="radio-one" name="switch-one" v-model="print" value="1" checked/>
                            <label for="radio-one">{{Yes}}</label>
                            <input type="radio" id="radio-two" name="switch-one" v-model="print" value="2" />
                            <label for="radio-two">{{Not}}</label>

                        </div>


                    </div>
                    <div class="text-center">
                        <button :disabled="disabled" @click.prevent="Pay" class="btn btn-warning ">{{text7}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PayModal",
        props:['order_id'],
        data() {
            return {
                disabled:false,
                print: 1,
                text1: '',
                text2: '',
                text3: '',
                text4: '',
                text5: '',
                text6: "",
                text7: "",
                Yes: '',
                Not: ''
            }
        },
        computed: {

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
        created() {
            this.text1 = this.$store.state.lang.PayModal[LanguneThisJs];
            this.text2 = this.$store.state.lang.PayModalPrice[LanguneThisJs];
            this.text3 = this.$store.state.lang.PayModalDiscount[LanguneThisJs];
            this.text4 = this.$store.state.lang.PayModalTotal[LanguneThisJs];
            this.text5 = this.$store.state.lang.PayModalNal[LanguneThisJs];
            this.text6 = this.$store.state.lang.PayModalPrint[LanguneThisJs];
            this.text7 = this.$store.state.lang.PayModalPay[LanguneThisJs];
            this.Yes = this.$store.state.lang.ClearorderModalYes[LanguneThisJs];
            this.Not = this.$store.state.lang.ClearorderModalNot[LanguneThisJs];
        },
        methods: {
            Pay() {
                this.disabled=true;
                this.$store.dispatch('Pay',{order_id:this.order_id,print:this.print})
                    .then(()=>{
                        this.disabled=false;
                    });
            }
        }
    }
</script>

<style scoped lang="scss">
    .title {
        font-size: 16px;
        font-weight: 500;
        text-align: center;
    }

    .type {
        display: flex;
        flex-wrap: wrap;
        .item {
            text-align: center;
            max-width: 160px;
            border: 1px solid #808080;
            border-radius: 5px;
            padding: 25px;
            font-size: 16px;
            font-weight: 500;
            img {
                display: inline-block;
                margin-bottom: 28px;
            }
        }
    }
    .total-order {
        border: none;
    }
    .print {
        text-align: center;
        margin-bottom: 20px;
        display:flex;
        justify-content: center;
        align-items: center;
        .text{
            font-size: 16px;
            font-weight: 500;
            margin-right: 10px;
        }
    }
    .switch-field {
        display: flex;
        overflow: hidden;
    }

    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .switch-field label {
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        line-height: 1;
        text-align: center;
        padding: 8px 16px;
        margin-right: -1px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        transition: all 0.1s ease-in-out;
    }
    .switch-field label:hover {
        cursor: pointer;
    }
    .switch-field input:checked + label {
        background-color: #00bd1a;
        box-shadow: none;
    }
    .switch-field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }
    .switch-field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }

</style>