<template>
    <a href="" @click.prevent="PrintOrder" class="link-item link-blue">
        <img src="/img/chek.png" alt="chek">
        <p>{{$t('table_print')}}</p>
    </a>
</template>

<script>
    export default {
        name: "PrintTable",
        data() {
            return {
                disabledPrint:false
            }
        },
        computed: {
            order_id() {
                let index = this.$store.state.tables.map(item => parseInt(item.id)).indexOf(this.$store.state.TableCloseActive);
                return this.$store.state.tables[index].order_id;
            },
        },
        methods:{
            PrintOrder(){
                this.disabledPrint=true;
                axios.post('/order/SendPrint', {
                    order_id: this.order_id,
                    preliminary:true
                })
                    .then( (response)=> {
                        console.log(response);
                    })
                    .catch((error)=> {
                        console.log(error);
                    })
                    .finally(()=> {
                        this.disabledPrint=false;
                    });

            }
        }
    }
</script>
