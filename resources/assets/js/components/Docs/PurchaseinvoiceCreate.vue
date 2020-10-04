<template>
    <div>
        <div class="row" v-for="(item,ind) in items" :key="ind+'stock'">
            <div class="col-xs-6 col-sm-11 col-xs-xs-xs-12 sto">
                <div class="col-sm-6 col-xs-12 select_doc_container">
                    <label>Продукт</label>
                    <select
                            @input="setProduct('stock', $event,ind)"
                            class="select_doc"
                            name="id_stocks[]" required>
                        <option disabled selected value>{{$t('Choose')}}</option>
                        <option v-for="stock in stocks" :value="stock.id">{{stock.title}}</option>
                    </select>

                </div>
                <div class="col-sm-6 col-xs-12 absal"><label>{{$t('Cart2')}}</label>
                    <input required
                           @input="setKol('stock', $event,ind)"
                           :value="item.count_stocks"
                           name="count_stocks[]"
                           class="reason-inp" type="number" step="0.01" min="0" />
                </div>
            </div>
            <div class="col-xs-6 col-sm-1 col-xs-xs-xs-12 sto">
                <button  @click.prevent="remove('stock',ind)" class="delite"><img src="/img/delite.png" alt="delite"></button>
            </div>
        </div>
        <div class="row" v-for="(item,index2) in item_ings" :key="index2+'ing'">
            <div class="col-xs-6 col-sm-11 col-xs-xs-xs-12 sto">
                <div class="col-sm-6 col-xs-12 select_doc_container">
                    <label>{{$t('IngsTitle')}}</label>
                    <select
                            class="select_doc"
                            @input="setProduct('ing', $event,index2)"
                            name="id_ingredients[]" required>
                        <option disabled selected value>{{$t('Choose')}}</option>
                        <option v-for="ing in ings" :value="ing.id">{{ing.title}}</option>
                    </select>
                </div>
                <div class="col-sm-6 col-xs-12 absal">
                    <label>{{$t('Cart2')}}</label>
                    <input required
                           @input="setKol('ing', $event,index2)"
                           :value="item.count_ings"
                           name="count_ingredients[]"
                           class="reason-inp" type="number" step="0.01" min="0" />
                </div>
            </div>
            <div class="col-xs-6 col-sm-1 col-xs-xs-xs-12 sto">
                <button  @click.prevent="remove('ing',index2)" class="delite"><img src="/img/delite.png" alt="delite"></button>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 reason-btn">
                <input class="add_component" type="submit" @click.prevent="add('stock')" :value="$t('AddProduct')">
                <input class="add_component" type="submit" @click.prevent="add('ing')" :value="$t('AddIng')">
                <input class="create_invoice" type="submit"   :value="$t('CreateInvoice')">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "PurchaseinvoiceCreate.vue",
        components: {
        },
        data() {
            return {
                items: [],
                item_ings: [],
                stocks: [],
                ings: [],
                stock_items:[]
            }
        },
        created() {
            axios.get('/doc/getProducts')
                .then(response => {
                    response.data.stocks.forEach(function(el,index){
                        response.data.stocks[index].count=parseFloat(el.count)
                    });
                    this.stocks = response.data.stocks;
                    response.data.ings.forEach(function(el,index){
                        response.data.ings[index].count=parseFloat(el.count)
                    });
                    this.ings = response.data.ings;
                    this.causes = response.data.causes;
                })
                .catch(error => {
                    this.showShwal('error', this.$t('error'))
                })
                .finally(function () {
                });
        },
        methods:{
            add(type){
                if (type == 'stock') {
                    this.items.push({
                        count_stocks: "",
                        causes: "",
                        stocks: '',
                        count: 0
                    });
                }
                if (type == 'ing') {
                    this.item_ings.push({
                        count_ings: "",
                        causes: "",
                        ings: '',
                        count: 0
                    });
                }
                setTimeout(()=>{
                    $('.select_doc').select2();
                })

            },

            //установить продукт
            setProduct(type, event, index) {
                let v = event.target.value;
                if ('stock' == type) {
                    this.items[index].stocks = v;
                }
                if ('ing' == type) {
                    this.item_ings[index].ings = v;
                }
            },
            setKol(type, event, index) {
                let v = event.target.value;
                if ('stock' == type) {
                    this.items[index].count_stocks = v;
                }
                if ('ing' == type) {
                    this.item_ings[index].count_ings = v;
                }
            },
            remove(type, index) {
                if ('stock' == type) {
                    this.items.splice(index, 1);
                }
                if ('ing' == type) {
                    this.item_ings.splice(index, 1);
                }
            },
            submit(){
                console.log(this.stock_items)
            }
        }
    }
</script>
<style scoped>
    .sto{
        min-height: 85px;
    }
    .delite{
        position: absolute;
        top: 50%;
    }
    input.add_component[type="submit"]{
        width:auto !important;
        padding-left:10px;
        padding-right:10px;
        margin-right:0 !important;
    }
    input.create_invoice{
        margin-left: 30px;
    }
    @media (max-width: 654px){
        input.add_component[type="submit"]{
            width:100% !important;
            padding-left:0;
            padding-right:0;
        }
        input.create_invoice{
            width:100% !important;
            margin-left: 0;
        }
    }


</style>
