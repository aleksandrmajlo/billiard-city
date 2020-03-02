<template>
    <div class="textarea-wrapper">
        <textarea id="comment" class="wishes-to-order" :value="info"
                  @input="SetInfo($event)"
                  placeholder="Тут Ви можете написати побажання до замовлення"></textarea>
    </div>
</template>

<script>
    export default {
        name: "CommentOrder",
        props: ['order_id'],
        computed: {
            info() {
                return this.$store.state.info
            },
        },
        methods:{
            SetInfo(event){

                let v=event.target.value;
                axios.post('/order/setInfo',{
                    info:v,
                    order_id:this.order_id
                })
            }
        }

    }
</script>

<style scoped>

</style>