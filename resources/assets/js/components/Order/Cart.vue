<template>
    <div>
        <div v-if="ErrorCount.length>0">
               <div class="alert alert-warning" role="alert">{{text5}}</div>
               <div class="table-wrapper">
                   <table class="table order-priсe" align="center">
                       <thead>
                           <tr>
                               <td>{{text1}}</td>
                               <td>{{text2}}</td>
                               <td>{{text6}}</td>
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
            <div class="mobile-show">{{text1}}</div>
            <table class="table order-priсe" align="center">
                <thead>
                <tr>
                    <td>{{text1}}</td>
                    <td>{{text2}}</td>
                    <td>{{text3}}</td>
                    <td>{{text4}}</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(product,index) in cart" :data-title="product.title" v-tooltip="{ content: 'Не доступно ',
                                                                                             show: product.isOpen,
                                                                                             trigger: 'manual', }" >
                    <td>{{product.title}}</td>
                    <td :data-title="text2">
                        <div class="inner">
                            <div class="block-add">
                                <button class="btn btn-warning btn-sm" @click.prevent="Minus(product,index)" type="button">-</button>
                                <span>{{product.count}}</span>
                                <button class="btn btn-warning btn-sm" @click.prevent="Plus(product,index)" type="button">+</button>
                            </div>
                        </div>
                    </td>
                    <td :data-title="text3">
                        <div class="inner">{{product.price}}</div>
                    </td>
                    <td :data-title="text4">
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
        props:['order_id'],
        data(){
            return {
                text1:'',
                text2:'',
                text3:'',
                text4:'',
                text5:'',
                text6:'',
            }
        },
        created(){
            this.$store.dispatch('getOrder',this.order_id);
            this.text1 = this.$store.state.lang.Cart1[LanguneThisJs];
            this.text2 = this.$store.state.lang.Cart2[LanguneThisJs];
            this.text3 = this.$store.state.lang.Cart3[LanguneThisJs];
            this.text4 = this.$store.state.lang.Cart4[LanguneThisJs];
            this.text5 = this.$store.state.lang.Cart5[LanguneThisJs];
            this.text6 = this.$store.state.lang.Cart6[LanguneThisJs];
        },
        computed: {
            cart(){
                return this.$store.state.cart
            },
            ErrorCount(){
                return this.$store.state.ErrorCount
            },
        },
        methods:{
            Minus(product,index){
                this.$store.commit('AddMinusProduct',product);
                this.$store.commit('SetTotal');
                this.$store.dispatch('setReserveAndCart',{order_id:this.order_id})
            },
            Plus(product,index){
                this.$store.commit('AddPlusProduct',product)
                this.$store.commit('SetTotal');
                this.$store.dispatch('setReserveAndCart',{order_id:this.order_id})
            }
        }
    }
</script>

<style scoped>

</style>