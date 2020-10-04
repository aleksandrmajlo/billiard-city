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

                        <div class="item" :class="{active:billing==1}" @click="setBilling(1)">
                            <svg xmlns='http://www.w3.org/2000/svg'
                                 width='512' height='512'
                                 viewBox='0 0 512 512'>
                                <rect x='32' y='80' width='448' height='256' rx='16' ry='16'
                                      transform='translate(512 416) rotate(180)'
                                      style='fill:none;stroke:#000;stroke-linejoin:round;stroke-width:32px'/>
                                <line x1='64' y1='384' x2='448' y2='384'
                                      style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/>
                                <line x1='96' y1='432' x2='416' y2='432' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/>
                                <circle cx='256' cy='208' r='80' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/>
                                <path d='M480,160a80,80,0,0,1-80-80' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/>
                                <path d='M32,160a80,80,0,0,0,80-80' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/>
                                <path d='M480,256a80,80,0,0,0-80,80' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/>
                                <path d='M32,256a80,80,0,0,1,80,80' style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/>
                            </svg>
                            <!--                            <img src="/images/nal.png"/>-->
<!--                            <img src="/img/nal/cash-outline.svg" style="stroke:red" />-->
                            <div>{{$t('PayModalNal')}}</div>
                        </div>

                        <div class="item " :class="{active:billing==2}" @click="setBilling(2)">
                            <svg xmlns='http://www.w3.org/2000/svg' width='512' height='512'
                                 viewBox='0 0 512 512'><title>ionicons-v5-d</title>
                                <rect x='48' y='96' width='416' height='320' rx='56' ry='56'
                                      style='fill:none;stroke:#000;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px'/><line x1='48' y1='192' x2='464' y2='192' style='fill:none;stroke:#000;stroke-linejoin:round;stroke-width:60px'/><rect x='128' y='300' width='48' height='20' style='fill:none;stroke:#000;stroke-linejoin:round;stroke-width:60px'/></svg>
                            <div>{{$t('CartModalPay')}}</div>
                        </div>

                        <div class="item dis" :class="{active:billing==3}" @click="setBilling(3)">
                            <svg id="Layer_1" enable-background="new 0 0 480 480" height="512" viewBox="0 0 480 480" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m334.213 197.787c3.124 3.125 3.124 8.189 0 11.314l-78.333 78.333c-1.563 1.562-3.609 2.343-5.657 2.343-7.064 0-10.712-8.601-5.657-13.657l78.333-78.333c3.124-3.123 8.189-3.123 11.314 0zm-3.046 65.546c8.73 0 15.833 7.104 15.833 15.834s-7.103 15.833-15.833 15.833-15.834-7.103-15.834-15.833 7.104-15.834 15.834-15.834zm-.167 15.834c0 .185.333.185.333 0 0-.184-.333-.184-.333 0zm-62.333-78.334c0 8.73-7.104 15.834-15.834 15.834s-15.833-7.104-15.833-15.834 7.103-15.833 15.833-15.833 15.834 7.103 15.834 15.833zm-15.834-.166c-.222 0-.217.333 0 .333.22 0 .221-.333 0-.333zm195.167-118.667h-254.5c-4.418 0-8 3.582-8 8s3.582 8 8 8h254.5c8.822 0 16 7.178 16 16v252c0 8.822-7.178 16-16 16h-302v-16.937c0-4.418-3.582-8-8-8s-8 3.582-8 8v16.937h-98c-8.822 0-16-7.178-16-16v-44.063c0-5.915 3.15-11.271 8.223-13.979 54.312-28.993 54.297-106.934-.001-135.918-5.072-2.706-8.222-8.062-8.222-13.977v-44.063c0-8.822 7.178-16 16-16h98v16.937c0 4.418 3.582 8 8 8s8-3.582 8-8v-24.937c0-4.418-3.582-8-8-8h-106c-17.645 0-32 14.355-32 32v44.063c0 11.835 6.395 22.6 16.688 28.094 43.035 22.97 43.026 84.721.001 107.688-10.294 5.493-16.689 16.258-16.689 28.093v44.062c0 17.645 14.355 32 32 32h416c17.645 0 32-14.355 32-32v-252c0-17.645-14.355-32-32-32zm-302 93.385c0-4.418-3.582-8-8-8s-8 3.582-8 8v31.317c0 4.418 3.582 8 8 8s8-3.582 8-8zm-8 89.913c-4.418 0-8 3.582-8 8v31.317c0 4.418 3.582 8 8 8s8-3.582 8-8v-31.317c0-4.418-3.582-8-8-8z"/></svg>
                            <div>{{$t('SertModalPay')}}</div>
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
                            <input
                                    type="radio"
                                    id="radio-one"
                                    name="switch-one"
                                    v-model="print"
                                    value="1"
                                    checked
                            />
                            <label for="radio-one">{{$t('ClearorderModalYes')}}</label>
                            <input type="radio" id="radio-two" name="switch-one" v-model="print" value="2"/>
                            <label for="radio-two">{{$t('ClearorderModalNot')}}</label>
                        </div>
                    </div>
                    <div class="text-center">
                        <button
                                :disabled="disabled"
                                @click.prevent="Pay"
                                class="btn btn-warning"
                        >{{$t('PayModalPay')}}
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
        props: ["order_id"],
        data() {
            return {

                disabled: false,
                print: 1,
                billing: 1
            };
        },
        computed: {
            typeOrder() {
                return this.$store.state.typeOrder;
            },
            price() {
                return this.$store.state.price;
            },
            skidka() {
                return this.$store.state.skidka;
            },
            total() {
                return this.$store.state.total;
            }
        },
        methods: {
            Pay() {
                this.disabled = true;
                if (this.order_id == "table") {
                    this.$store
                        .dispatch("PayTable", {
                            print: this.print,
                            billing: this.billing
                        })
                        .then(() => {
                            this.disabled = false;
                            this.$store.dispatch("getTables");
                            setTimeout(() => {
                                $("#PayModal").modal("hide");
                                $('.ConteerRowTable').removeClass('TableOpen')
                                $('.ConteerRowTable').removeClass("TableOpenFree");

                            }, 300);
                        });
                } else {
                    this.$store
                        .dispatch("Pay", {
                            order_id: this.order_id,
                            billing: this.billing,
                            print: this.print
                        })
                        .then(() => {
                            this.disabled = false;
                        });
                }
            },
            setBilling(billing) {
                this.billing = billing;
            }
        }
    };
</script>
<style scoped>
    .item {
        cursor: pointer;

    }
    .item svg,
    .item img{
        display: block;
        margin: 25px auto;
        max-width: 48px;
        height: auto;
    }
    #PayModal .modal-content .modal-body .type .item{
        border-color: rgb(130, 130, 130);
    }
    .item.active div,
    .item.active svg path,
    .item.active svg rect,
    .item.active svg line,
    .item.active svg circle{
        stroke:#f2994a !important;
        color:#f2994a !important;
    }
</style>
