<template>
    <div class="modal fade modalCustom" id="SmsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-my" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="text">{{$t('SmsModal')}}</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="phoneBlock">
                        <div class="form-group">
                            <div v-if="error" class="alert alert-warning" role="alert">{{$t('SmsCodeError')}}</div>
                            <model-select :options="users" v-model="user" :placeholder="$t('SmsModalSMS')"></model-select>
                        </div>
                    </div>
                    <div class="buttonBlock">
                        <div class="itemBtton leftItem">
                            <a class="btn btn-primary" href="#" @click.prevent="SMS(2)">{{$t('SmsModalPhone')}}</a>
                        </div>
                        <div class="itemBtton rightItem">
                            <a class="btn btn-primary" href="#" @click.prevent="SMS(1)">{{$t('SmsModalBell')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {ModelSelect} from "vue-search-select";
    export default {
        name: "SmsModal",
        props: ["order_id"],
        components: {
            ModelSelect
        },
        data() {
            return {
                error: false,
                user: {
                    value: "",
                    text: ""
                }
            };
        },
        computed: {
            users() {
                return this.$store.state.users;
            }
        },
        created() {
            this.$store.dispatch("GetUsers");
        },
        methods: {
            SMS(typesms) {
                //дзвинок - 1
                //смс - 2
                if (this.user.value == "") {
                    this.error = true;
                    return false;
                } else {
                    this.error = false;
                    this.$store
                        .dispatch("SendSMS", {
                            user: this.user,
                            phones: this.user.text,
                            typesms: typesms
                        })
                        .then(() => {
                            $("#SmsModal").modal("hide");
                            $("#SmsCode").modal("show");
                            this.user = {
                                value: "",
                                text: ""
                            }
                        });
                }
            }
        }
    };
</script>
<style scoped lang="scss">
    .phoneBlock {
        margin-bottom: 28px;
    }
</style>