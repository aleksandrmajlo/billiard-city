<template>
    <div class="conteerProduct">
        <h2>Продукт</h2>

        <div class="itemCont">
            <div class="item " v-for="(item,ind) in items" :key="ind">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>Продукт</label>
                        <select class="form-control"
                                @input="setProduct('stock', $event,ind)"
                                name="stocks[]" required>
                            <option disabled selected value>Вибрати</option>
                            <option v-for="stock in stocks" :value="stock.id">{{stock.title}}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Кол.</label>
                        <input required
                               @input="setKol('stock', $event,ind)"
                               :value="item.count_stocks"
                               name="count_stocks[]"
                               class="form-control" type="number" step="0.01" min="1"/>
                        <span v-if="!item.valid" class="text-danger">Максимально:{{item.count}}</span>
                    </div>
                    <div class="form-group col-md-5">
                        <div class="form-group">
                            <label>Причина</label>
                            <select class="form-control" name="stock_causes[]">
                                <!--<option disabled selected value>Вибрати</option>-->
                                <option v-for="cause in causes" :value="cause">{{cause}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="stock_causeTexts[]"
                                      placeholder="Cвоя причина"></textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-1">
                        <a href="#" class="remove" @click.prevent="remove('stock',ind)">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="btnCont">
            <a class="btn btn-default" @click.prevent="add('stock')">Добавити</a>
        </div>
         <hr>
        <h2>{{Text_ings}}</h2>
        <div class="itemCont">
            <div class="item " v-for="(item,index2) in item_ings" :key="index2">
                <div class="row">
                    <div class="form-group col-md-3">
                        <label>{{Text_ing}}</label>
                        <select class="form-control"
                                @input="setProduct('ing', $event,index2)"
                                name="ings[]" required>
                            <option disabled selected value>Вибрати</option>
                            <option v-for="ing in ings" :value="ing.id">{{ing.title}}</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Кол.</label>
                        <input required
                               @input="setKol('ing', $event,index2)"
                               :value="item.count_ings"
                               name="count_ings[]"
                               class="form-control" type="number" step="0.01" min="1"/>
                        <span v-if="!item.valid" class="text-danger">Максимально:{{item.count}}</span>
                    </div>
                    <div class="form-group col-md-5">
                        <div class="form-group">
                            <label>Причина</label>
                            <select class="form-control" name="ing_causes[]">
                                <!--<option disabled selected value>Вибрати</option>-->
                                <option v-for="cause in causes" :value="cause">{{cause}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="ing_causeTexts[]"
                                      placeholder="Cвоя причина"></textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-1">
                        <a href="#" class="remove" @click.prevent="remove('ing',index2)">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="btnCont">
            <a class="btn btn-default" @click.prevent="add('ing')">Добавити</a>
        </div>


    </div>
</template>
<script>
    export default {
        name: "WriteofProducts",
        data() {
            return {
                items: [],
                item_ings: [],
                stocks: [],
                ings: [],

                causes: [],
                lang: {

                    Text_ings: {
                        'ru': 'Составляющие',
                        'ua': 'Складові'
                    },

                    Text_ing: {
                        'ru': 'Составляющая',
                        'ua': 'Складова'
                    },

                }
            }
        },
        computed: {

            Text_ings() {
                return this.lang.Text_ings[LanguneThisJs]
            },
            Text_ing() {
                return this.lang.Text_ing[LanguneThisJs]
            },

        },
        created() {
            axios.get('/doc/getProducts')
                .then(response => {
                    this.stocks = response.data.stocks;
                    this.ings = response.data.ings;
                    this.causes = response.data.causes;
                })
                .catch(error => {
                })
                .finally(function () {
                });
        },
        methods: {
            add(type) {
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
    .item {
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid;
    }

    .btnCont {
        margin-bottom: 20px;
    }

    a.remove {
        font-size: 26px;
    }
</style>