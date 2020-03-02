<template>
    <div class="board_table" v-if="TableCloseActive">
        <table>
            <tr>
                <td>номер стола</td>
                <td>
                    <img src="/img/stol.png" alt="stol">
                </td>
                <td>
                    <p>стіл № {{number}} {{name}}</p>
                </td>
            </tr>
            <tr>
                <td>номер замовлення</td>
                <td><img src="/img/number.png" alt="namber"></td>
                <td>
                    <p>{{order_id}}</p>
                </td>
            </tr>
            <tr>
                <td>дата відкриття</td>
                <td><img src="/img/colendar-s.png" alt="colendar"></td>
                <td>
                    <p>{{startDate}}</p>
                </td>
            </tr>
            <tr>
                <td>час відкриття</td>
                <td><img src="/img/clock.png" alt="clock"></td>
                <td>
                    <p> {{startM}}</p>
                </td>
            </tr>
            <tr>
                <td>Відвідувач</td>
                <td><img src="/img/contact.png" alt="contact"></td>
                <td>
                    <p>{{customer.name}}</p>
                </td>
            </tr>
            <tr>
                <td>Стіл відкритий</td>
                <td><img src="/img/clock.png" alt="clock"></td>
                <td>
                    <p>{{minutes|minutes_houres}} <span>відкритий</span></p>
                </td>
            </tr>
            <tr>
                <td>Ціна за хвилину</td>
                <td><img src="/img/many.png" alt="many"></td>
                <td>
                    <p>{{priceOrderMinutes}}</p>
                </td>
            </tr>
            <tr>
                <td>Паузи</td>
                <td><img src="/img/pause.png" alt="pause"></td>
                <td>
                    <p v-for="(pause,index) in pauses">
                        {{pause.startM}} - {{pause.endM}}
                        <span v-if="pause.minpause_this">
                            пауза  {{pause.minpause_this}}хв
                        </span>
                        <span v-else>
                            триває
                        </span>
                    </p>
                </td>
            </tr>
            <tr>
                <td>Розрахунок знижки</td>
                <td><img src="/img/procent.png" alt="procent"></td>
                <td>
                    <p class="procent"> {{priceProcentOrder}}<span>знижка</span></p>
                    <p class="procent">{{priceOrder}} ₴ <span>сумма</span></p><br>
                    <p class="procent">{{priceOrderDiscount}} ₴<span>знижка</span></p>
                    <p class="procent"> {{priceOrderTotal}} ₴<span>кінцева ціна</span></p>
                </td>
            </tr>
        </table>
        <div class="board_link">
            <a href="" @click.prevent="SendSms" class="link-item link-orange">
                <img src="/img/link-procent.png" alt="procent">
                <p>знижка</p>
            </a>

            <a  v-if="activePause" @click.prevent="SetPause" href="#" class="link-item link-orange">
                <img src="/img/link-pause.png" alt="pause">
                <p>відновити</p>
            </a>
            <a href="#" v-else @click.prevent="SetPause" class="link-item link-orange ">
                <img src="/img/link-pause.png" alt="pause">
                <p>пауза</p>
            </a>

            <print-table></print-table>
            <a href="" @click.prevent="CloseTableOrder" class="link-item link-blues">
                <img src="/img/zakaz.png" alt="zakaz">
                <p>Закрити
                </p>
            </a>
        </div>
        <a class="back backTable" @click.prevent='Back' href=""><img src="/img/back.png" alt="back"></a>
    </div>
</template>

<script>
    export default {
        name: "CloseTable",
        data() {
            return {
                startDate: '',
                startM: '',
                customer: '',
                minutes: '',
                priceOrderMinutes: '',
                pauses: [],
                priceProcentOrder: 0,
                priceOrder: 0,
                priceOrderDiscount: 0,
                priceOrderTotal: 0,
                activePause: false,
            }
        },
        computed: {
            TableCloseActive() {
                return this.$store.state.TableCloseActive
            },
            number() {
                let index = this.$store.state.tables.map(item => parseInt(item.id)).indexOf(this.$store.state.TableCloseActive);
                return this.$store.state.tables[index].number;
            },
            name() {
                let index = this.$store.state.tables.map(item => parseInt(item.id)).indexOf(this.$store.state.TableCloseActive);
                return this.$store.state.tables[index].name;
            },
            order_id() {
                if (this.$store.state.TableCloseActive) {
                    let index = this.$store.state.tables.map(item => parseInt(item.id)).indexOf(this.$store.state.TableCloseActive);
                    return this.$store.state.tables[index].order_id;
                } else {
                    return false;
                }
            },
        },

        watch: {
            TableCloseActive: function (id, old_id) {
                this.UpdateActiveTable();
            }
        },
        mounted() {
            this.$root.$on('UpdateActiveTableMoun', (item, response) => {
                this.UpdateActiveTable();
            })
        },
        methods: {
            SetPause() {
                this.swal();
                axios.post('/table/SetPause', {
                    table: this.$store.state.TableCloseActive,
                    pause: this.activePause,
                    order: this.order_id
                }).then(response => {
                    this.$swal.close();
                    this.UpdateActiveTable();
                })
            },
            Back() {
                $('.ConteerRowTable').removeClass('TableOpen')
            },
            SendSms() {
                $('#SmsModal').modal('show');
            },
            CloseTableOrder() {
                $('#PayModal').modal('show');
            },

            UpdateActiveTable() {
                if (this.order_id) {
                    this.swal();
                    return axios.post('/table/GetTablePrice', {
                        id: this.order_id
                    })
                        .then(response => {
                            this.$swal.close();
                            this.startDate = response.data.results.startDate;
                            this.startM = response.data.results.startM;
                            this.customer = response.data.results.customer;

                            this.minutes = response.data.results.minutes;
                            this.priceOrderMinutes = response.data.results.priceOrderMinutes;

                            this.pauses = response.data.results.pauses;
                            this.activePause = response.data.results.activePause;


                            this.priceProcentOrder = response.data.results.priceProcentOrder;
                            this.priceOrder = response.data.results.priceOrder;
                            this.priceOrderDiscount = response.data.results.priceOrderDiscount;
                            this.priceOrderTotal = response.data.results.priceOrderTotal;

                            this.$store.commit('SetTableTotaldata',{
                                total:this.priceOrderTotal,
                                skidka:this.priceProcentOrder,
                                price:this.priceOrder
                            })

                        });
                }


            },
            swal(){
                this.$swal.fire({
                    icon: 'error',
                    text: 'Почекайте...',
                    showConfirmButton: false,
                    closeOnClickOutside: false
                });
            }
        }

    }
</script>
