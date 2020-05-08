<template>
    <div class="col-xs-5  widthFullFree" v-if="TableAddActive">
        <div class="board_table">
            <table>
                <tr>
                    <td>номер столу</td>
                    <td>
                        <img src="/img/stol.png" alt="stol">
                    </td>
                    <td>
                        <p>стіл № {{number}} {{name}}</p>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        Контактна особа
                        <div class="radio">
                            <input id="guest" type="radio" v-model="user" value="guest">
                            <label for="guest">Гість</label>
                            <input id="client" type="radio" v-model="user" value="client">
                            <label for="client">Клієнт</label>
                        </div>
                    </td>
                    <td colspan="2">
                        <div v-show="user=='client'">
                            <div class="alert alert-warning" v-if="errorSms">
                                Виберіть користувача
                            </div>
                            <model-select :options="users" v-model="client" :placeholder="text4"></model-select>
                            <button :disabled="disabledClient" @click.prevent="SMS">підтвердити</button>
                        </div>
                    </td>
                </tr>
                <tr v-show="user=='client'">
                    <td valign="top">Тип ідентифікації
                        <div class="radio">
                            <input id="sms" type="radio" v-model="typesms" value="2">
                            <label for="sms">SMS</label>
                            <input id="phone" type="radio" v-model="typesms" value="1">
                            <label for="phone">Phone</label>
                        </div>
                    </td>
                    <td colspan="2">
                        <div v-if="showCode">
                            <div v-show="validateCodeError" class="alert alert-error">
                                Невірно
                            </div>
                            <input type="text" class="" :placeholder="text2" v-model="code"/>
                            <button :disabled="disabledCode"
                                    @click.prevent="checkCode">підтвердити
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
            <div v-show="user=='guest'||validateCode" class="board_link one">
                <a href="#"
                   @click.prevent="AddTable"
                   class="link-item link-orange">
                    <img src="/img/link-open.png" alt="procent">
                    <p>відкрити стіл</p>
                </a>
            </div>
            <a class="back backTable" @click.prevent='Back' href=""><img src="/img/back.png" alt="back"></a>
        </div>
    </div>
</template>
<script>
    import {ModelSelect} from "vue-search-select";

    export default {
        name: "TableAdd",
        components: {
            ModelSelect
        },
        data() {
            return {
                user: 'guest',
                client: {
                    value: "",
                    text: ""
                },
                typesms: 2,
                disabledClient: false,// кнопка отправки первая
                disabledCode: false,// кнопка отправки кода
                showCode: false,// если отправлено подтверждение телефона
                validateCode: false,// валидация кода
                validateCodeError: false,// валидация кода
                errorSms: false,
                code: ''
            }
        },
        computed: {
            TableAddActive() {
                return this.$store.state.TableAddActive
            },
            number() {
                let index = this.$store.state.tables.map(item => parseInt(item.id)).indexOf(this.$store.state.TableAddActive);
                return this.$store.state.tables[index].number;
            },
            name() {
                let index = this.$store.state.tables.map(item => parseInt(item.id)).indexOf(this.$store.state.TableAddActive);
                return this.$store.state.tables[index].name;
            },
            users() {
                return this.$store.state.users
            },
            text4() {
                if ('ru' == LanguneThisJs) {
                    return 'Введите номер телефона';
                } else {
                    return 'Введіть номер телефону';
                }
            },
            text2() {
                if ('ru' == LanguneThisJs) {
                    return 'Введите код';
                } else {
                    return 'Введіть код';
                }
            },
            id_sms() {
                return this.$store.state.id_sms;
            },

        },
        mounted() {
            this.$root.$on('SetClientNull', (item, response) => {
                this.SetClientNullValue();
            })
        },
        methods: {
            AddTable() {
                this.$swal.fire({
                    icon: 'error',
                    text: 'Почекайте...',
                    showConfirmButton: false,
                    closeOnClickOutside: false
                });
                axios.post('/table/AddTable', {
                    table: this.TableAddActive,
                    user: this.user,
                    client: this.client.value
                }).then(response => {
                    this.$swal.close();
                    let id = this.TableAddActive;
                    this.$store.commit('SetTableAddActive', false);
                    this.$store.dispatch('getTables').then(() => {
                        this.$store.commit('SetTableactive', id);
                    });
                })
            },
            SMS() {
                //дзвинок - 1
                //смс - 2
                if (this.client.value == "") {
                    this.errorSms = true;
                    return false;
                } else {
                    this.errorSms = false;
                    this.disabledClient = true;
                    this.$store
                        .dispatch("SendSMS", {
                            user: this.client,
                            phones: this.client.text,
                            typesms: this.typesms
                        })
                        .then(() => {
                            this.showCode = true;

                        });
                }
            },
            checkCode() {
                this.disabledCode = true;
                this.validateCodeError = false;
                let data = {
                    cod: this.id_sms,
                    codes: this.code,
                    ajaxmy: true,
                };
                return axios.post('/checkCode', data)
                    .then(response => {
                        if (response.data.res == 1) {
                            this.validateCode = true;
                        } else {
                            this.validateCodeError = true;
                        }
                        this.disabledCode = false;
                    })
                    .catch(error => {

                    })

            },
            Back() {
                $('.ConteerRowTable').removeClass('TableOpenFree')
            },
            // установить на ноль телефон
            SetClientNullValue() {
                this.client = {
                    value: "",
                    text: ""
                };
            }

        }
    }
</script>
<style>
    .ui.search.dropdown > input.search {
        margin-top: 0 !important;
    }
</style>