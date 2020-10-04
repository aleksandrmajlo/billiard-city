<template>
    <div class="user_table" v-cloak>
        <loading :active.sync="isLoading"
                 :can-cancel="true"
                 :is-full-page="fullPage"></loading>
        <table v-cloak class="tableCustomers">
            <tr>
                <td>ID</td>
                <td>{{$t('fio')}}</td>
                <td>E-MAIL</td>
                <td>{{$t('date_of_birth')}}</td>
                <td>{{$t('phone_number')}}</td>
                <td>
                    <p>%</p>
                    <p>Бар</p>
                </td>
                <td>
                    <p>%</p>
                    <p>{{$t('billiard')}}</p>
                </td>
            </tr>
            <tr v-for="customer in customers" :key="customer.id">
                <td>
                    <a :href="'/customers/'+customer.id">{{customer.id}}</a>
                </td>
                <td>
                    <a :href="'/customers/'+customer.id">
                        {{customer.name}} {{customer.surname}}
                    </a>
                </td>
                <td>
                    {{customer.email}}
                </td>
                <td>
                    {{customer.birthday}}
                </td>
                <td>
                    {{customer.phone}}
                </td>
                <td>{{customer.skidka_bar}}</td>
                <td>{{customer.skidka}}</td>
            </tr>
        </table>
    </div>
</template>
<script>
    import {eventBus} from '~/app'
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    export default {
        name: "ListCustomers",
        props: ['page'],
        components: {
            Loading
        },
        data() {
            return {
                customers: [],
                q: "",
                isLoading: false,
                fullPage: true
            }
        },
        created() {
            this.default();
            eventBus.$on('searchCustomers', data => {
                this.q = data.q;
                if (this.q == "") {
                    this.default();
                    $('ul.pagination').removeClass('hidden')
                } else {
                    this.search();
                    $('ul.pagination').addClass('hidden')
                }
            })
        },
        methods: {
            default() {
                axios.get('/customers?page=' + this.page)
                    .then(response => {
                        this.customers = response.data.customers.data;
                    })
                    .catch
                    (error => {
                        this.showShwal('error', this.$t('error'));
                    }).then(() => {});
            },
            search() {
                this.isLoading = true;
                axios.get('/customer/' + this.q)
                    .then(response => {
                        this.customers = response.data.customers;
                    })
                    .catch(error => {
                    })
                    .then(() => {
                        setTimeout(() => {
                            this.isLoading = false;
                        }, 500)
                    });
            }
        }
    }
</script>
