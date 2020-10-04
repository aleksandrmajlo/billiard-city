<template>
    <div>
        <div class="row">
            <div class="col-md-10">
                <div class="inventory">
                    <div class="inventory_title">
                        <h1>{{text1}} - {{title}}</h1>
                        <span :style="{borderColor:borderColorActivePage}">{{ActivePage}}</span>
                    </div>
                    <div class="inventory_table">
                        <table>
                            <tr>
                                <td>{{text2}}</td>
                                <td>{{text3}}</td>
                                <td>{{text4}}</td>
                            </tr>
                            <tr v-for="(product,index) in products[ActiveCategory]"
                                :key="product.id"
                                :style="{backgroundColor:product.color}"
                                class="parentTR"
                            >
                                <td>
                                    <p class="tea">
                                        {{product.title}}
                                    </p>
                                </td>
                                <td>
                                    <span> {{product.count}}</span>
                                </td>
                                <td>
                                    <input type="number" min="0" step="0.01"
                                           :id="'result'+product.id"
                                           :value="product.result"
                                           @blur="setBackgroundColor(product.id)"
                                           @input="setCount(product.id,$event)"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="batton_block">
                        <div class="atention">
                            <p>{{text6}}</p>
                        </div>
                        <div class="button-cover activ">
                            <div class="button b2" id="button-chek">
                                <input type="checkbox" v-model="knobs" class="checkbox"/>
                                <div class="knobs">
                                    <span>off</span>
                                </div>
                            </div>
                        </div>
                        <div v-show="!LastStep" class="action activ">
                            <a href @click.prevent="Next">{{text5}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="navigation__block">
                    <p class="short">Катег.</p>
                    <p class="long">Категорії</p>
                    <ul class="navigation">
                        <li v-for="(cat,index) in cats" :key="index">
                            <a @click.prevent="setActiveCategory(cat.id)" :style="{  borderColor:cat.color  }" href="#">{{index+1}}</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="row" v-show="LastStep">
            <div class="col-md-10">
                <div class="inventory">
                    <div class="inventory_table two">
                        <table>
                            <tr>
                                <td>{{text2}}</td>
                                <td>{{text3}}</td>
                                <td>{{text4}}</td>
                            </tr>
                            <tr id="kava_tr">
                                <td>
                                    <p class="tea">
                                        {{kava.title}}
                                    </p>
                                </td>
                                <td>
                                    <span> {{kava.count}}</span>
                                </td>
                                <td>
                                    <input type="number" min="0"
                                           step="1"
                                           id="kava_id"
                                           @blur="setBackgroundColorKava"
                                           @input="setCountKava"
                                           :value="kava.result">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

        <div class="row" v-show="LastStep">
            <div class="col-md-10">
                <div class="inventory">
                    <div class="inventory_table two">
                        <table>
                            <tr>
                                <td><img src="/img/table-many.png" alt=""></td>
                                <td>{{text7}}</td>
                                <td>{{text4}}</td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="cash">{{text8}}</p>
                                </td>
                                <td><span>{{summaChange}} ₴</span></td>
                                <td>
                                    <input type="number" min="0" v-model="summa" name="cash">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="action change">
                        <a @click.prevent="Submit" href="#">{{text9}}</a>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>


    </div>

</template>

<script>
    export default {
        name: "close_barmen",
        data() {
            return {
                ActiveCategory: '',//активная категория ID

                products: {},
                ingredients: [],
                cats: [
                    {
                        id: "ingredients",
                        title: "Інгредієнти",
                        color: '#1a1aff',
                        count: '',
                        result: ''
                    }
                ],
                kava: {
                    id: "kava",
                    title: "Кавовий апарат",
                    color: '#ad7751',
                    count: '',
                    result: ''
                },
                knobs: false,
                summaChange: 0,
                ChangeId: false,
                summa: '',
                lang: {
                    text1: {
                        ru: "Инвентаризация (закрытие смены)",
                        ua: "Інвентаризація (закриття зміни)"
                    },

                    text2: {
                        ru: "Наименование",
                        ua: "Найменування"
                    },

                    text3: {
                        ru: "На складе",
                        ua: "На складі"
                    },

                    text4: {
                        ru: "Фактически",
                        ua: "Фактично"
                    },

                    text5: {
                        ru: "ДАЛЕЕ",
                        ua: "ДАЛІ"
                    },

                    text6: {
                        ru: "*Игнорировать ошибку и перейти к следующей категории",
                        ua: "*Ігнорувати  помилку і перейти до наступної категорії"
                    },

                    text7: {
                        ru: "Денег в кассе",
                        ua: "Грошей в касі"
                    },


                    text8: {
                        ru: "Сверьте количество денег в кассе",
                        ua: "Звірте кількість грошей в касі"
                    },
                    text9: {
                        ru: "Закрыть смену",
                        ua: "Закрити зміну"
                    },
                }
            };
        },
        created() {

            axios
                .get("/change_data/category")
                .then(response => {
                    response.data.cats.forEach(element => {
                        this.cats.push(element);
                    });
                    this.products = response.data.products;
                    this.ActiveCategory = 'ingredients';
                    this.summaChange = response.data.summaChange;
                    this.ChangeId = response.data.ChangeId;
                    this.kava.count = response.data.kavaCount;

                    // для тэста ******************
                    if('p.billiard-city.local'==location.hostname){
                        this.tt();
                    }

                })
                .catch(error => {
                })
                .finally(function () {
                });

        },
        computed: {
            title() {
                let i = this.cats.map(item => item.id).indexOf(this.ActiveCategory);
                if (i !== -1) {
                    return this.cats[i].title;
                }
                return '';
            },
            ActivePage() {
                let i = this.cats.map(item => item.id).indexOf(this.ActiveCategory);
                if (i !== -1) {
                    return i + 1;
                }
                return '';
            },
            borderColorActivePage() {
                let i = this.cats.map(item => item.id).indexOf(this.ActiveCategory);
                if (i !== -1) {
                    return this.cats[i].color;
                }
                return '';
            },
            LastStep() {
                let i = this.cats.map(item => item.id).indexOf(this.ActiveCategory);
                if ((i + 1) == this.cats.length) {
                    return true;
                } else {
                    return false;
                }
            },

            text1() {
                return this.lang.text1[LanguneThisJs];
            },
            text2() {
                return this.lang.text2[LanguneThisJs];
            },
            text3() {
                return this.lang.text3[LanguneThisJs];
            },
            text4() {
                return this.lang.text4[LanguneThisJs];
            },
            text5() {
                return this.lang.text5[LanguneThisJs];
            },
            text6() {
                return this.lang.text6[LanguneThisJs];
            },
            text7() {
                return this.lang.text7[LanguneThisJs];
            },
            text8() {
                return this.lang.text8[LanguneThisJs];
            },
            text9() {
                return this.lang.text9[LanguneThisJs];
            },

        },
        methods: {
            // переход на следущий шаг
            Next() {
                if (this.validateChangeCat()) {
                    let i = this.cats.map(item => item.id).indexOf(this.ActiveCategory);
                    let next = this.cats[i + 1];
                    this.ActiveCategory = next.id;
                    window.scrollTo(0, 10);
                } else {
                    this.$swal.fire({
                        icon: 'error',
                        text: 'Перевірте дані',
                        showConfirmButton: false,
                        closeOnClickOutside: false
                    });
                    setTimeout(() => {
                        this.$swal.close();
                    }, 2000)
                }
            },
            //установка категории
            setActiveCategory(id) {
                if (this.ActiveCategory == id) return false;
                if (this.validateChangeCat()) {
                    this.ActiveCategory = id;
                    window.scrollTo(0, 10);
                } else {
                    this.$swal.fire({
                        icon: 'error',
                        text: 'Перевірте дані',
                        showConfirmButton: false,
                        closeOnClickOutside: false
                    });
                    setTimeout(() => {
                        this.$swal.close();
                    }, 2000)
                }
            },

            // установка кол-ва товара
            setCount(id, event) {
                let val = event.target.value;
                let i = this.products[this.ActiveCategory].map(item => item.id).indexOf(id);
                Vue.set(this.products[this.ActiveCategory][i], 'result', val);
            },
            // установка кофе
            setCountKava(event) {
                let val = event.target.value;
                this.kava.result = val;
            },
            //устанавливаем ошибку на tr
            setBackgroundColor(id) {
                let i = this.products[this.ActiveCategory].map(item => item.id).indexOf(id);
                if (parseFloat(this.products[this.ActiveCategory][i].count) == parseFloat(this.products[this.ActiveCategory][i].result)) {
                    Vue.set(this.products[this.ActiveCategory][i], 'color', "");
                    $('#result' + id).parents('.parentTR').css("background-color", "")
                } else {
                    Vue.set(this.products[this.ActiveCategory][i], 'color', '#e2aaaa');
                    $('#result' + id).parents('.parentTR').css("background-color", '#e2aaaa')
                }
            },
            //кофе ошибка
            setBackgroundColorKava() {
                if (parseInt(this.kava.result) == parseInt(this.kava.count)) {
                    $('#kava_tr').css("background-color", "")
                } else {
                    $('#kava_tr').css("background-color", "#e2aaaa")
                }
            },
            // валидация при смене категории
            validateChangeCat() {
                let empty = false;
                let discrepancy = false;
                this.products[this.ActiveCategory].forEach((product) => {
                    if (typeof product.result == "undefined" || product.result == "") {
                        empty = true;
                    }
                    if (parseFloat(product.result) !== parseFloat(product.count)) {
                        discrepancy = true;
                    }
                    this.setBackgroundColor(product.id)
                });
                if (empty) {
                    return false;
                }
                //если выключена кнопка и  есть несоответствие
                if (!this.knobs && discrepancy) {
                    return false;
                }
                return true;
            },
            //  валидация  при закрытии смены
            validateSubmit() {
                this.setBackgroundColorKava();
                if (this.kava.result == "") {
                    return false;
                }
                if (!this.knobs && (parseInt(this.kava.result) !== parseInt(this.kava.count))) {
                    return false;
                }
                return true;
            },
            // отправка формы
            Submit() {
                if (this.validateSubmit()) {
                    this.showShwal('info',this.$t('change_send'),false)
                    let data = {
                        products: this.products,
                        summa: this.summa,
                        ChangeId: this.ChangeId,
                        kofeinyi_apparat: this.kava.result
                    };
                    axios
                        .post("/change_data/Submit", data)
                        .then(response => {
                            location = response.data.url;
                            this.$swal.close();
                        })
                        .catch(error => {
                            this.$swal.close();
                            this.showShwal('error',this.$t('error'));
                        })
                        .finally(function () {
                        });
                }
            },
            tt() {
                var self = this;
                for (var i in this.products) {
                    this.products[i].forEach((product) => {
                        Vue.set(product, 'result', 50.11);
                    })
                }
            }

        }
    };
</script>
