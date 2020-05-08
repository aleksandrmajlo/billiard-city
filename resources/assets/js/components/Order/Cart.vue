<template>
    <div>
        <div v-if="ErrorCount.length>0">
            <div class="alert alert-warning" role="alert">{{$t('Cart5')}}</div>
            <div class="table-wrapper">
                <table class="table order-priсe" align="center">
                    <thead>
                    <tr>
                        <td>{{$t('Cart1')}}</td>
                        <td>{{$t('Cart2')}}</td>
                        <td>{{$t('Cart6')}}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(err,ind) in ErrorCount" :key="ind">
                        <td>{{err.title}}</td>
                        <td>{{err.count}}</td>
                        <td>{{err.countThis}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="table-wrapper">
            <div class="mobile-show">{{$t('Cart1')}}</div>
            <table class="table order-priсe" align="center">
                <thead>
                <tr>
                    <td>{{$t('Cart1')}}</td>
                    <td>{{$t('Cart2')}}</td>
                    <td>{{$t('Cart3')}}</td>
                    <td>{{$t('Cart4')}}</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product,index) in cart" :data-title="product.title" v-tooltip="{ content: 'Не доступно ',
                                                                                             show: product.isOpen,
                                                                                             trigger: 'manual', }">
                    <td>{{product.title}}</td>
                    <td :data-title="$t('Cart2')">
                        <div class="inner">
                            <div class="block-add">
                                <button class="btn btn-warning btn-sm" @click.prevent="Minus(product,index)"
                                        type="button">-
                                </button>
                                <span>{{product.count}}</span>
                                <button class="btn btn-warning btn-sm" @click.prevent="Plus(product,index)"
                                        type="button">+
                                </button>
                            </div>
                        </div>
                    </td>
                    <td :data-title="$t('Cart3')">
                        <div class="inner">{{product.price}}</div>
                    </td>
                    <td :data-title="$t('Cart4')">
                        <div class="inner">{{product.total}}</div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script>
    export default {
        name: "Cart",
        props: ['order_id'],
        data() {
            return {
            }
        },
        created() {
            this.$store.dispatch('getOrder', this.order_id);
        },
        computed: {
            cart() {
                return this.$store.state.cart
            },
            ErrorCount() {
                return this.$store.state.ErrorCount
            },
        },
        methods: {
            Minus(product, index) {
                this.showShwal('info',this.$t('suc_save'),false);
                this.$store.commit('AddMinusProduct', product);
                this.$store.commit('SetTotal');
                this.$store.dispatch('setReserveAndCart', {order_id: this.order_id}).then(()=>{
                    this.$swal.close()
                })
            },
            Plus(product, index) {
                this.showShwal('info',this.$t('suc_save'),false);
                this.$store.commit('AddPlusProduct', product)
                this.$store.commit('SetTotal');
                this.$store.dispatch('setReserveAndCart', {order_id: this.order_id}).then(()=>{
                    this.$swal.close()
                })
            }
        }
    }
</script>
<style scoped>
    .preloaderCart {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 100;
    }
    .inner_preloaderCart {
        display: flex;
        justify-content: center;
        align-items: center;
        color:red;
    }
    .inner_preloaderCart h2{
        margin-top: 20px;
        font-size: 31px;
        font-weight: 600;
    }
</style>