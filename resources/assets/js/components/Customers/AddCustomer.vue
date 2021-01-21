<template>
    <form>
        <div class="row">
            <div class="col-xs-4 col-xs-xs-xs-6">
                <label>{{$t('name')}}</label>
            </div>

            <div class="col-xs-8 col-xs-xs-xs-6">
                <input type="text" v-model="name" :placeholder="$t('name_plac')">
            </div>

            <div class="col-xs-4 col-xs-xs-xs-6">
                <label>{{$t('last_name')}}</label>
            </div>

            <div class="col-xs-8 col-xs-xs-xs-6">
                <input type="text" v-model="surname" :placeholder="$t('last_name_plac')">
            </div>

            <div class="col-xs-4 col-xs-xs-xs-6">
                <label>{{$t('date_of_birth')}}</label>
            </div>

            <div class="col-xs-8 col-xs-xs-xs-6">
                <input type="date" v-model="birthday" placeholder="число - месяц - год">
            </div>

            <div class="col-xs-4 col-xs-xs-xs-6">
                <label>e - mail</label>
            </div>

            <div class="col-xs-8 col-xs-xs-xs-6">
                <input type="email" v-model="email" placeholder="name@mail.com">
            </div>

            <div class="col-xs-6 col-xs-xs-12">
                <div class="col-sm-8 col-xs-7 col-xs-xs-xs-6 no-pdd"><label>{{$t('skidka_bill')}}</label></div>
                <div class="col-sm-4 col-xs-5 col-xs-xs-xs-6 pdd-left">
                    <imask-input
                            v-model="skidka"
                            :mask="Number"
                            :unmask="true"
                            :min="0"
                            :max="100"
                            :lazy="false"
                            placeholder="0%"
                    />
                </div>
            </div>

            <div class="col-xs-6  col-xs-xs-12">
                <div class="col-sm-8 col-xs-7 col-xs-xs-xs-6 mob-podd"><label>{{$t('skidka_bar')}}</label></div>
                <div class="col-sm-4 col-xs-5 col-xs-xs-xs-6 no-pdd">
                    <imask-input
                            v-model="skidka_bar"
                            :mask="Number"
                            :min="0"
                            :max="100"
                            :unmask="true"
                            :lazy="false"
                            placeholder="0%"
                    />
                </div>
            </div>


            <div  v-if="isadmin">


                <div class="col-xs-6 col-xs-xs-12">

                    <div class="col-xs-12 col-xs-xs-10 col-xs-xs-xs-12 no-pdd" >
                        <imask-input
                                v-model="phone"
                                :mask="'+{38}(000)000-00-00'"
                                :unmask="true"
                                type="text"
                                :class="{isInvalid:phoneError}"
                                :lazy="false"
                                @complete="onComplete"
                                @accept="onAccept"
                                :placeholder="$t('login_phone_plac')"
                        />
                    </div>



                </div>

                <div class="col-xs-12 saves" >
                    <input class="cancel" @click.prevent="cancel" type="submit" :value="$t('cancel')">
                    <input class="save" :disabled="disabled"  @click.prevent="send" type="submit" :value="$t('save')">
                </div>

            </div>

            <div  v-else>

                <div class="col-xs-6 col-xs-xs-12">
                    <div class="col-xs-12 col-xs-xs-10 col-xs-xs-xs-12 no-pdd" v-if="!showCode">
                        <imask-input
                                v-model="phone"
                                :mask="'+{38}(000)000-00-00'"
                                :unmask="true"
                                type="text"
                                :class="{isInvalid:phoneError}"
                                :lazy="false"
                                @complete="onComplete"
                                @accept="onAccept"
                                :placeholder="$t('login_phone_plac')"
                        />
                        <span v-show="phoneError" class="text-warning">{{$t('SmsCode')}}</span>
                    </div>

                    <div class="col-xs-12 col-xs-xs-10 col-xs-xs-xs-12 no-pdd" v-else-if="!isCodeValid">
                        <imask-input
                                v-model="code"
                                :mask="'0000'"
                                :unmask="true"
                                type="text"
                                :class="{isInvalid:codeError}"
                                :lazy="false"
                                @complete="onCompleteCode"
                                @accept="onAcceptCode"
                                :placeholder="$t('login_code')"
                        />
                        <span v-show="codeError" class="text-warning">{{$t('SmsCodeErrorEnter')}}</span>
                    </div>

                </div>

                <div class="col-xs-6 col-xs-xs-12 ">

                    <div class="col-xs-6 col-xs-xs-5 col-xs-xs-xs-6 no-pdd" v-if="!showCode">
                        <div class="radio">
                            <input id="sms" type="radio" value="2" v-model="typesms">
                            <label class="label" for="sms">SMS</label>
                            <input id="phone" type="radio" name="user" value="1" v-model="typesms">
                            <label class="label" for="phone">Phone</label>
                        </div>
                    </div>

                    <div class="col-xs-6 col-xs-xs-5 col-xs-xs-xs-6 no-pdd " v-if="!showCode">
                        <input class="sab" type="submit" :disabled="disabled" @click.prevent="sendSMS"
                               :value="$t('send_phone')">
                    </div>

                    <div class="col-xs-6 col-xs-xs-5 col-xs-xs-xs-6 no-pdd " v-else-if="!isCodeValid">
                        <input class="sab" type="submit" :disabled="disabled" @click.prevent="checkCode"
                               :value="$t('send_phone')">
                    </div>

                </div>
                <div class="col-xs-12">
                    <input class="note" type="text" v-model="description" :placeholder="$t('comment')">
                </div>

                <div class="col-xs-12 saves" v-if="isCodeValid">
                    <input class="cancel" @click.prevent="cancel" type="submit" :value="$t('cancel')">
                    <input class="save" :disabled="disabled"  @click.prevent="send" type="submit" :value="$t('save')">
                </div>

           </div>




        </div>
    </form>
</template>

<script>
    import {IMaskComponent} from 'vue-imask';
    import {eventBus} from '~/app'
    export default {
        name: "AddCustomer",
        components: {
            'imask-input': IMaskComponent
        },
        props: ['isadmin'],
        data() {
            return {
                id_sms: "",

                phoneValid: false,
                phoneError: false,
                code: "",
                showCode: false,// показать поля для вода кода
                isCodeValid: false,
                codeError: false,
                disabled: false,
                typesms: 2,//тип

                // поля пользователя
                phone: "",
                name: "",
                surname: "",
                email: "",
                birthday: "",
                skidka: "",
                skidka_bar: "",
                description: ""
            }
        },
        methods: {
            onAccept() {
                this.phoneValid = false
            },
            onComplete() {
                this.phoneValid = true
            },
            //отправка смс
            sendSMS() {
                if (!this.phoneValid) {
                    this.phoneError = true;
                    return false;
                }
                this.phoneError = false;
                this.disabled = true;
                //дзвинок - 1
                //смс - 2
                axios.post('/generateCode', {
                    phones: this.phone,
                    typesms: this.typesms,
                    ajaxmy: true
                }).then(response => {
                    this.id_sms = response.data.id_sms;
                    this.showCode = true;
                }).catch(error => {
                    this.showShwal('error', this.$t('error'));
                }).then(() => {
                    this.disabled = false;
                })
            },
            // проверка кода
            onCompleteCode() {
            },
            onAcceptCode() {
            },
            checkCode() {

                this.disabled = true;
                this.codeError = false;
                axios.post('/checkCode',
                    {
                        cod: this.id_sms,
                        codes: this.code,
                        ajaxmy: true,
                    }
                ).then(response => {
                    if (response.data.res == 1) {
                        // ответ верный
                        this.isCodeValid = true;
                    } else {
                        this.codeError = true;
                    }
                }).catch(error => {
                    this.showShwal('error', this.$t('error'));
                }).then(() => {
                    this.disabled = false;
                })
            },
            // очистить
            cancel() {
                this.id_sms = "",
                    this.phoneValid = false,
                    this.phoneError = false,
                    this.code = "",
                    this.showCode = false,// показать поля для вода кода
                    this.isCodeValid = false,
                    this.codeError = false,
                    this.disabled = false;
                this.phone = "",
                    this.name = "",
                    this.surname = "",
                    this.email = "",
                    this.birthday = "",
                    this.skidka = "",
                    this.skidka_bar = "",
                    this.description = "";
                 document.getElementById('closeCompareActWin').click();
            },
            send() {
                if (!this.phoneValid) {
                    this.phoneError = true;
                    return false;
                }
                this.phoneError = false;
                this.disabled = true;
                axios.post('/customers', {
                    phone: this.phone,
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    birthday: this.birthday,
                    skidka: this.skidka,
                    skidka_bar: this.skidka_bar,
                    description: this.description
                }).then(response => {
                   if(response.data.success){
                       this.cancel();
                       document.getElementById('closeCompareActWin').click();
                       this.showShwal('success', this.$t('customersAdd'));
                       eventBus.$emit('searchCustomers', {
                           q: "",
                       });
                   }else {
                       this.showShwal('error', this.$t('error'));
                   }
                }).catch(error => {
                    this.showShwal('error', this.$t('error'));
                }).then(() => {
                    this.disabled = false;
                })
            }
        }
    }
</script>
