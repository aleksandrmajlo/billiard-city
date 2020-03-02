<template>
    <div class="modal fade modalCustom" id="SmsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-my" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="text">{{text1}}</div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="phoneBlock">
                        <div class="form-group">
                            <div v-if="error" class="alert alert-warning" role="alert">{{text5}}</div>
                            <model-select :options="users" v-model="user" :placeholder="text4"></model-select>
                        </div>
                    </div>
                    <div class="buttonBlock">
                        <div class="itemBtton leftItem">
                            <a class="btn btn-primary" href="#" @click.prevent="SMS(2)">{{text2}}</a>
                        </div>
                        <div class="itemBtton rightItem">
                            <a class="btn btn-primary" href="#" @click.prevent="SMS(1)">{{text3}}</a>
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
                text1: "",
                text2: "",
                text3: "",
                text4: "",
                text5: "",

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
            this.text1 = this.$store.state.lang.SmsModal[LanguneThisJs];
            this.text2 = this.$store.state.lang.SmsModalSMS[LanguneThisJs];
            this.text3 = this.$store.state.lang.SmsModalBell[LanguneThisJs];
            this.text4 = this.$store.state.lang.SmsModalPhone[LanguneThisJs];
            this.text5 = this.$store.state.lang.SmsCodeError[LanguneThisJs];
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