<template>
    <div>
        <div class="row" v-for="(item,ind) in items" :key="ind+'stock'">
            <div class="col-xs-6 col-sm-8 col-xs-xs-xs-12 sto">
                <div class="col-sm-6 col-xs-12 select_doc_container">
                    <label>Продукт</label>
                    <select
                            class="select_doc"
                            @input="setProduct('stock', $event,ind)"
                            name="stocks[]" required>
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
                    <span v-if="!item.valid" class="text-danger">Максимально:{{item.count}}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-xs-xs-xs-12 sto">
                <label>Причина</label>
                <select class="reason-select" name="stock_causes[]">
                    <option v-for="cause in causes" :value="cause">{{cause}}</option>
                </select>
                <input type="text" name="stock_causeTexts[]" :placeholder="$t('CauseOther')" class="reason-inp">
                <button  @click.prevent="remove('stock',ind)" class="delite"><img src="/img/delite.png" alt="delite"></button>
            </div>
        </div>
        <div class="row" v-for="(item,index2) in item_ings" :key="index2+'ing'">
            <div class="col-xs-6 col-sm-8 col-xs-xs-xs-12 sto">
                <div class="col-sm-6 col-xs-12 select_doc_container">
                    <label>{{$t('IngsTitle')}}</label>
                    <select
                            class="select_doc"
                            @input="setProduct('ing', $event,index2)"
                            name="ings[]" required>
                        <option disabled selected value>{{$t('Choose')}}</option>
                        <option v-for="ing in ings" :value="ing.id">{{ing.title}}</option>
                    </select>
                </div>
                <div class="col-sm-6 col-xs-12 absal">
                    <label>{{$t('Cart2')}}</label>
                    <input required
                           @input="setKol('ing', $event,index2)"
                           :value="item.count_ings"
                           name="count_ings[]"
                           class="reason-inp" type="number" step="0.01" min="0" />
                    <span v-if="!item.valid" class="text-danger">Максимально:{{item.count}}</span>
                </div>
            </div>
            <div class="col-xs-6 col-sm-4 col-xs-xs-xs-12 sto">
                <label>Причина</label>
                <select class="reason-select" name="ing_causes[]">
                    <option v-for="cause in causes" :value="cause">{{cause}}</option>
                </select>
                <input type="text" name="ing_causeTexts[]" :placeholder="$t('CauseOther')" class="reason-inp">
                <button  @click.prevent="remove('ing',index2)" class="delite"><img src="/img/delite.png" alt="delite"></button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 reason-btn">
                <input class="add_component" type="submit" @click.prevent="add('stock')" :value="$t('AddProduct')">
                <input class="add_component" type="submit" @click.prevent="add('ing')" :value="$t('AddIng')">
                <input class="create_invoice" type="submit"  :value="$t('CreateInvoice')">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "WriteofProducts.vue",
        data() {
            return {
                items: [],
                item_ings: [],
                stocks: [],
                ings: [],
                causes: [],
            }
        },
        created() {

            axios.get('/doc/getProducts')
                .then(response => {
                    console.info(response.data);

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
                        valid: true,
                        count: 0
                    });
                }
                if (type == 'ing') {
                    this.item_ings.push({
                        count_ings: "",
                        causes: "",
                        ings: '',
                        valid: true,
                        count: 0
                    });
                }
                setTimeout(()=>{
                    $('.select_doc').select2();
                })
            },
            submit(){
                console.info('submit')
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
                this.validate(type, index);
            },
            setKol(type, event, index) {
                let v = event.target.value;
                if ('stock' == type) {
                    this.items[index].count_stocks = v;
                }
                if ('ing' == type) {
                    this.item_ings[index].count_ings = v;
                }
                this.validate(type, index);
            },
            remove(type, index) {
                if ('stock' == type) {
                    this.items.splice(index, 1);
                }
                if ('ing' == type) {
                    this.item_ings.splice(index, 1);
                }
            },
            //валидация
            validate(type, index) {
                if ('stock' == type) {
                    let id = this.items[index].stocks;
                    let count = this.items[index].count_stocks;
                    if (count == "") return false;
                    this.stocks.forEach((element, ind) => {
                        if (element.id == id) {
                            if (parseFloat(count) > parseFloat(element.count)) {
                                this.items[index].valid = false;
                                this.items[index].count_stocks = element.count;
                            } else {
                                this.items[index].valid = true;
                            }
                            this.items[index].count = element.count;
                        }
                    });
                }
                if ('ing' == type) {
                    let id = this.item_ings[index].ings;
                    let count = this.item_ings[index].count_ings;
                    if (count == "") return false;
                    this.ings.forEach((element, ind) => {
                        if (element.id == id) {
                            if (parseFloat(count) > parseFloat(element.count)) {
                                this.item_ings[index].valid = false;
                                this.item_ings[index].count_ings = element.count;
                            } else {
                                this.item_ings[index].valid = true;
                            }
                            this.item_ings[index].count = element.count;
                        }
                    });
                }
            }
        }
    }
</script>
<style scoped>
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
