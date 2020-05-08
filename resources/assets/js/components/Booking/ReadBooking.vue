<template>
    <div class="tabs" v-show="showAddForm||showReadForm">
        <a @click.prevent="hideTabs" class="close-tab">
            <img src="/img/close.png" alt="close"/>
        </a>

        <ul class="tabs__caption">
            <li :class="{active:showReadForm}" @click="setActiveReadForm">Редагування</li>
            <li :class="{active:showAddForm}" @click="setActiveAddForm">Додати бронь</li>
        </ul>

        <div :class="{active:showReadForm}" class="tabs__content">
            <div class="reservation__block form-user">
                <form v-on:submit.prevent>
                    <div class="row">
                        <div class="col-xs-4 col-xs-xs-12">
                            <label>Ім'я</label>
                        </div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <input v-model="booking.name" type="text" placeholder="Эрик Максимович"/>
                        </div>

                        <div class="col-xs-4 col-xs-xs-12">
                            <label>Прізвище</label>
                        </div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <input v-model="booking.lastname" type="text" placeholder="Казаков "/>
                        </div>

                        <div class="col-xs-4 col-xs-xs-12"><label>Телефон</label></div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <input v-model="booking.phone" type="tel" placeholder="+34 --- --- -- --">
                        </div>
                        <div class="col-xs-4 col-xs-xs-12"><label>E-mail</label></div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <input v-model="booking.email" type="email" placeholder="name@.com">
                        </div>

                        <div class="col-xs-4 col-xs-xs-12">
                            <label>Номер столу</label>
                        </div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <model-select :options="tables" v-model="booking.table"></model-select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="col-lg-8 col-md-4 col-xs-4 col-xs-xs-12 lg-pdd right-pdd right-podd">
                                <label>Дата початку броні:</label>
                            </div>
                            <div class="col-lg-4 col-md-8 col-xs-8 col-xs-xs-12 pdd-left">
                                <input v-model="booking.from" type="date" placeholder="24.01.2020"/>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="col-sm-4 col-xs-4 col-xs-xs-12 md-pdd-left">
                                <label>Час початку:</label>
                            </div>
                            <div class="col-sm-8 col-xs-8 col-xs-xs-12 lg-pdd">
                                <p class="user-p">
                                    <input v-model="booking.from_time" type="time" placeholder="15:30"/>
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="col-lg-8 col-md-4 col-xs-4 col-xs-xs-12 lg-pdd right-pdd right-podd">
                                <label>Дата закінчення броні</label>
                            </div>
                            <div class="col-lg-4 col-md-8 col-xs-8 col-xs-xs-12 pdd-left">
                                <input v-model="booking.to" type="date" placeholder="24.01.2020"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="col-sm-4 col-xs-4 col-xs-xs-12 md-pdd-left">
                                <label>Час закінчення:</label>
                            </div>
                            <div class="col-sm-8 col-xs-8 col-xs-xs-12 lg-pdd">
                                <p class="user-p">
                                    <input v-model="booking.to_time" type="time" placeholder="15:30"/>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-4 col-xs-xs-12">
                            <div class="no-pdd">
                                <label>Cтатус броні :</label>
                            </div>
                        </div>
                        <div class="col-lg-5 col-xs-8 col-xs-xs-12">
                            <div class="lg-pdd">
                                <div class="radio-confirm">
                                    <input v-model="booking.status" id="confirm" type="radio" name="user1" value="0"/>
                                    <label class="label" for="confirm">Підтверджено</label>
                                    <input v-model="booking.status" id="Requir" type="radio" name="user1" value="1"/>
                                    <label class="label" for="Requir">
                                        Вимагає  підтвердження
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <input v-model="booking.note" class="note" type="text" name placeholder="Примітка"/>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xs-4 col-xs-xs-12">
                            <div class="no-pdd">
                                <label>Джерело :</label>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-8 col-xs-8 col-xs-xs-12">
                            <div class="lg-pdd">
                                <div class="radio-source">
                                    <input
                                            v-model="booking.source"
                                            id="phones"
                                            type="radio"
                                            name="user3"
                                            value="phone"
                                    />
                                    <label class="label" for="phones">Телефон</label>
                                    <input
                                            v-model="booking.source"
                                            id="email"
                                            type="radio"
                                            name="user3"
                                            value="email"
                                    />
                                    <label class="label" for="email">E-mail</label>
                                    <input
                                            v-model="booking.source"
                                            id="social"
                                            type="radio"
                                            name="user3"
                                            value="socials"
                                    />
                                    <label class="label" for="social">Соц. сети</label>
                                    <input v-model="booking.source" id="site" type="radio" name="user3" value="site"/>
                                    <label class="label" for="site">Сайт</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 removes">
                            <input
                                    @click.prevent="removeBooking(booking.id)"
                                    class="remove"
                                    type="submit"
                                    value="Вилучити"
                            />
                            <input @click.prevent="saveBooking" class="save" type="submit" :value="text2"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div :class="{active:showAddForm}" class="tabs__content">
            <div class="reservation__block form-user">
                <form v-on:submit.prevent>
                    <div class="row">

                        <div class="col-xs-4 col-xs-xs-12">
                            <label>Ім'я</label>
                        </div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <input v-model="add.name" type="text" placeholder="Эрик Максимович"/>
                        </div>
                        <div class="col-xs-4 col-xs-xs-12">
                            <label>Прізвище</label>
                        </div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <input v-model="add.lastname" type="text" placeholder="Казаков "/>
                        </div>

                        <div class="col-xs-4 col-xs-xs-12">
                            <label>Телефон</label>
                        </div>

                        <div class="col-xs-8 col-xs-xs-12">
                            <autocomplete
                                    v-on:setCustomer="setCustomerAddForm"
                                    v-on:changePhone="changePhone"
                                    :customers="customers"></autocomplete>
                        </div>
                        <div class="col-xs-4 col-xs-xs-12"><label>E-mail</label></div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <input v-model="add.email" type="email" placeholder="name@.com">
                        </div>

                        <div class="col-xs-4 col-xs-xs-12">
                            <label>Номер столу</label>
                        </div>
                        <div class="col-xs-8 col-xs-xs-12">
                            <model-select :options="tables" v-model="add.table"></model-select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="col-lg-8 col-xs-4 col-xs-xs-12 lg-pdd right-pdd right-podd">
                                <label>Дата початку броні :</label>
                            </div>
                            <div class="col-lg-4 col-xs-8 col-xs-xs-12 pdd-left">
                                <input v-model="add.from" type="date" placeholder="24.01.2020"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="col-xs-4 col-xs-xs-12 md-pdd-left">
                                <label>Час початку:</label>
                            </div>
                            <div class="col-xs-8 col-xs-xs-12 lg-pdd">
                                <p class="user-p">
                                    <input v-model="add.from_time" type="time" placeholder="15:30"/>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="col-lg-8 col-xs-4 col-xs-xs-12 lg-pdd right-pdd right-podd">
                                <label>Дата закінчення броні :</label>
                            </div>
                            <div class="col-lg-4 col-xs-8 col-xs-xs-12 pdd-left">
                                <input v-model="add.to" type="date" placeholder="24.01.2020"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="col-xs-4 col-xs-xs-12 md-pdd-left">
                                <label>Час закінчення:</label>
                            </div>
                            <div class="col-xs-8 col-xs-xs-12 lg-pdd">
                                <p class="user-p">
                                    <input v-model="add.to_time" type="time" placeholder="15:30"/>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-xs-4 col-xs-xs-12">
                            <div class="lg-pdd">
                                <label>Статус броні :</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-8 col-xs-xs-12">
                            <div class="lg-pdd">
                                <div class="radio-confirm">
                                    <input v-model="add.status" id="confirm1" type="radio" name="user4" value="0"/>
                                    <label class="label" for="confirm1">Підтверджено</label>
                                    <input v-model="add.status" id="Requir1" type="radio" name="user4" value="1"/>
                                    <label class="label" for="Requir1">Вимагає підтвердження</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <input v-model="add.note" class="note" type="text" name id placeholder="Замітка"/>
                        </div>
                        <div class="col-xs-4 col-xs-xs-12">
                            <div class="no-pdd">
                                <label>Джерело :</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-xs-8 col-xs-xs-12">
                            <div class="no-pdd">
                                <div class="radio-source">
                                    <input v-model="add.source" id="phones1" type="radio" name="user5" value="phone"/>
                                    <label class="label" for="phones1">Телефон</label>
                                    <input v-model="add.source" id="email1" type="radio" name="user3" value="email"/>
                                    <label class="label" for="email1">E-mail</label>
                                    <input
                                            v-model="add.source"
                                            id="social1"
                                            type="radio"
                                            name="user5"
                                            value="socials"
                                    />
                                    <label class="label" for="social1">Соц. сети</label>
                                    <input v-model="add.source" id="site1" type="radio" name="user5" value="site"/>
                                    <label class="label" for="site1">Сайт</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 removes">
                            <input @click.prevent="hideAddForm" class="remove" type="submit" :value="text1"/>
                            <input @click.prevent="submitAddForm" class="save" type="submit" :value="text2"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from "../../app";
    import {ModelSelect} from "vue-search-select";

    export default {
        name: "ReadBooking",
        components: {
            ModelSelect
        },
        data() {
            return {
                tables: [],
                showAddForm: false,
                showReadForm: false,
                users: [],
                customers: [],
                componentKey: 0,

                add: {
                    id_customers: '',
                    name: "",
                    lastname: "",
                    phone: "",
                    table: "",
                    from: "",
                    from_time: "",
                    to: "",
                    to_time: "",
                    status: "",
                    note: "",
                    source: "",
                    email: ''
                },
                booking: {

                    id: "",
                    id_customers: '',
                    name: "",
                    lastname: "",
                    phone: "",
                    table: "",
                    from: "",
                    from_time: "",
                    to: "",
                    to_time: "",
                    status: "",
                    note: "",
                    source: "",
                    email: ""

                },
                lang: {
                    text1: {
                        ru: "Отменить",
                        ua: "Відмініти"
                    },
                    text2: {
                        ru: "Сохранить",
                        ua: "Зберегти"
                    }
                }
            };
        },
        computed: {
            text1() {
                return this.lang.text1[LanguneThisJs];
            },
            text2() {
                return this.lang.text2[LanguneThisJs];
            }
        },
        created() {

            let today = new Date().toISOString().substr(0, 10);
            this.add.to = today;
            this.add.from = today;
            axios
                .get("/booking_ajax/tables")
                .then(response => {
                    this.tables = response.data.tables;
                    this.customers = response.data.customers;
                })
                .catch(error => {
                })
                .finally(function () {
                });

            eventBus.$on("showAddBookingForm", () => {
                this.showAddForm = true;
                this.showReadForm = false;
            });
            eventBus.$on("showReadFormOn", booking => {
                this.showAddForm = false;
                this.showReadForm = true;
                this.booking = booking;
            });
        },
        methods: {
            setCustomerAddForm(id) {
                let index = this.customers.map(item => parseInt(item.id)).indexOf(id);
                if (index !== -1) {
                    this.add.phone = this.customers[index].phone;
                    this.add.email = this.customers[index].email;
                    this.add.name = this.customers[index].name;
                    this.add.lastname = this.customers[index].surname;
                    this.add.id_customers = this.customers[index].id;
                }
            },
            changePhone(val) {
                this.add.phone = val;
            },
            submitAddForm() {
                if (
                    this.add.table == "" ||
                    this.add.from == "" ||
                    this.add.from_time == "" ||
                    this.add.to == "" ||
                    this.add.to_time == "" ||
                    this.add.status == "" ||
                    this.add.phone == ""
                ) {
                    this.showShwal(
                        "error",
                        this.$t('booking_error_email')
                    );

                    return false;
                }
                if(this.add.email!==""&&this.add.email!==null&&!this.ValidateEmail(this.add.email)){
                    this.showShwal(
                        "error",
                        this.$t('booking_error_email')
                    );
                    return false;
                }
                axios
                    .post("/booking_ajax/addNewbooking", this.add)
                    .then(response => {
                        this.showShwal("success", this.$t('booking_add'));
                        this.showAddForm = false;
                        eventBus.$emit("updateBookings");
                        this.$emit('setSearchPhone', '');
                        let today = new Date().toISOString().substr(0, 10);
                        this.add = {
                            id_customers: '',
                            name: "",
                            lastname: "",
                            phone: "",
                            table: "",
                            from: today,
                            from_time: "",
                            to: today,
                            to_time: "",
                            status: "",
                            note: "",
                            source: ""
                        }
                    })
                    .catch(error => {
                    })
                    .finally(function () {
                    });
            },
            hideAddForm() {
                this.showAddForm = false;
                window.scrollTo(0, 10);
            },
            saveBooking() {
                if (
                    (this.booking.table == "" || this.booking.table === null) ||
                    this.booking.from == "" ||
                    this.booking.from_time == "" ||
                    this.booking.to == "" ||
                    this.booking.to_time == "" ||
                    this.booking.status === "" ||
                    this.booking.phone == ""
                ) {
                    this.showShwal(
                        "error",
                        this.$t('booking_error')
                    );
                    return false;
                }
                if(this.booking.email!==""&&this.booking.email!==null&&!this.ValidateEmail(this.booking.email)){
                    this.showShwal(
                        "error",
                        this.$t('booking_error_email')
                    );
                    return false;
                }
                axios
                    .post("/booking_ajax/saveBooking", this.booking)
                    .then(response => {
                        this.showShwal("success", this.$t('booking_save'));
                        eventBus.$emit("updateBookings");
                    })
                    .catch(error => {
                    })
                    .finally(function () {
                    });
            },
            removeBooking(id) {
                axios
                    .post("/booking_ajax/removeBooking", {id: id})
                    .then(response => {
                        this.booking = {
                            id: "",
                            name: "",
                            lastname: "",
                            phone: "",
                            table: "",
                            from: "",
                            from_time: "",
                            to: "",
                            to_time: "",
                            status: "",
                            note: "",
                            source: ""
                        };
                        this.showReadForm = false;
                        eventBus.$emit("updateBookings");
                    })
                    .catch(error => {
                    })
                    .finally(function () {
                    });
            },
            hideTabs() {
                this.showAddForm = false;
                this.showReadForm = false;
                window.scrollTo(0, 10);
            },
            setActiveAddForm() {
                if (!this.showAddForm) {
                    this.showReadForm = false;
                    this.showAddForm = true;
                }
            },
            setActiveReadForm() {
                if (!this.showReadForm && this.booking.id !== "") {
                    this.showReadForm = true;
                    this.showAddForm = false;
                    return;
                }
                if (!this.showReadForm && this.booking.id == "") {
                    this.showShwal('info', this.$t('booking_er'))
                }
            },
            ValidateEmail(email) {
                if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
                    return (true)
                }
                return (false)
            }
        }
    };
</script>

<style scoped>
    .form-user form select {
        width: 100%;
        height: 30px;
        background: rgb(255, 255, 255);
        border-radius: 50px;
        border: none;
        margin-bottom: 10px;
        padding-left: 20px;
        box-sizing: border-box;
    }

    .ui.selection.dropdown {
        height: 30px !important;
        background: rgb(255, 255, 255) !important;
        border-radius: 50px !important;
        border: none;
        padding-left: 20px;
        box-sizing: border-box;
        margin-bottom: 10px;
        padding-top: 7px;
        padding-bottom: 7px;
    }


</style>
<style>
    @media only screen and (min-width: 992px){
        .tabs__content .ui.selection.dropdown .menu {
            max-height: 10.68571429rem !important;
        }
    }

</style>