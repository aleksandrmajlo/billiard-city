<template>
        <div class="user_table" v-cloak>
            <loading :active.sync="isLoading"
                     :can-cancel="true"
                     :is-full-page="fullPage"></loading>
            <table v-cloak class="tableCustomers">
                <tr>
                    <td class="td-left">{{$t('nameTR')}}</td>
                    <td v-if="law"></td>
                </tr>
                <tr v-for="category in categories" :key="category.id">
                    <td class="td-left">
                        {{ category.title }}
                    </td>
                    <td v-if="law">
                        <edit-cat :category="category"></edit-cat>
                    </td>
                </tr>
            </table>
        </div>
</template>

<script>
    import {eventBus} from '~/app'
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';
    import EditCat from '~/components/Bar/EditCat.vue'
    export default {
        name: "ListCat",
        props: ['page','law'],
        components: {
            Loading,
            EditCat
        },
        data() {
            return {
                categories: [],
                q: "",
                isLoading: false,
                fullPage: true
            }
        },
        created() {
            this.default();
            eventBus.$on('searchCats', data => {
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
        methods:{
            default() {
                axios.get('/bars/categories?page=' + this.page)
                    .then(response => {
                        this.categories = response.data.categories.data;
                    })
                    .catch
                    (error => {
                        this.showShwal('error', this.$t('error'));
                    }).then(() => {});
            },
            search() {
                this.isLoading = true;
                axios.get('/bars/category/' + this.q)
                    .then(response => {
                        this.categories = response.data.categories;
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
