<template>
    <imask-input
            v-model="phone"
            :mask="'+{3}0(000)000-00-00'"
            :unmask="true"
            type="phone"
            :lazy="false"
            @complete="onComplete"
            @accept="onAccept"
            :placeholder="$t('login_phone_plac')"
    />
</template>

<script>
    import {IMaskComponent} from 'vue-imask';
    export default {
        name: "Phone",
        props:['val'],
        data(){
            return{
                phone:this.val
            }
        },
        components: {
            'imask-input': IMaskComponent
        },
        watch:{
            phone(){
                if($('[name="phone"]').length){
                    $('[name="phone"]').val(this.phone)
                }
            }
        },
        methods:{
            onAccept(){

            },
            onComplete(){

            },
        }
    }
</script>

