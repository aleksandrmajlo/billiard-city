<template>
    <div class="right-site-block" v-if="showCategories">
        <div class="position-item" v-for="(category,index) in categories" :key="index" @click="setProduct(category)">
            <p>{{category.title}}</p>
            <img :src="category.image" alt="">
        </div>
    </div>

</template>

<script>
    export default {
        name: "Category",
        created() {
            this.$store.dispatch("CategoriesGet");
        },
        computed: {
            showCategories(){
                return this.$store.state.showCategories
            },
            categories() {
                return this.$store.state.categories
            }
        },
        methods:{
            setProduct(category){
                this.$store.dispatch("CategoriesProducts",category.id);
            }
        }
    }
</script>

<style scoped>
   .position-item{
        cursor: pointer;
   }
</style>