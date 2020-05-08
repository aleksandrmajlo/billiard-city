<template>
    <div class="right-site-block" v-if="!showCategories">
        <div class="position-item" v-for="(product,index) in products" :key="index" @click="AddCart(product)">
            <p>{{product.title}}</p>
            <img :src="product.image" alt="">
            <div v-if="product.unlimited" class="count">More</div>
            <div v-else class="count">{{product.count}}</div>

        </div>
    </div>
</template>

<script>
    export default {
        name: "Products",
        props:['order_id'],
        computed: {
            showCategories(){
                return this.$store.state.showCategories
            },
            products() {
                return this.$store.state.products
            }
        },
        methods: {
            AddCart(product){
                this.$swal.fire({
                    icon: 'info',
                    text:  'Йде збереження, почекайте!!',
                    showConfirmButton:false,
                    closeOnClickOutside: false
                });
                this.$store.commit('AddCart',product);
                this.$store.commit('SetTotal');
                this.$store.dispatch('setReserveAndCart',{order_id:this.order_id}).then(()=>{
                    this.$swal.close()
                })
            }
        }
    }
</script>

<style scoped>
   .position-item{
        cursor: pointer;
       position: relative;
   }
    .count{
        bottom: 5px;
        right: 5px;
        background: #fff;
        border-radius: 5px;
        color: #000000;
        position: absolute;
        padding: 5px;
    }
</style>