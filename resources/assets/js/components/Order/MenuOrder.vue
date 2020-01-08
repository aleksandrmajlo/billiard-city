<template>
    <div class="categories-menu clearfix">
            <span v-for="(menu,index) in menus">
                 <button @click.prevent="clickMenu(menu)" href="#" class="btn btn-primary">{{menu.title}}</button>
            </span>
            <span>
                <button id="SearchButton" class="btn btn-warning pull-right" @click.prevent="ShowHideSearch" type="button">
                   <img src="/images/search-button.png" alt="">
                </button>
            </span>

    </div>
</template>

<script>
    export default {
        name: "MenuOrder",
        mounted() {

        },
        computed: {
            menus() {
                return this.$store.state.menus
            }

        },
        methods: {
            clickMenu(menu) {
                this.$store.commit("MenuClick", menu);
            },
            ShowHideSearch() {
                this.$root.$emit("ShowHideSearch", {data: false});
            }
        }

    }
</script>

<style scoped>
    span {
        margin-right: 14px;
    }
</style>