<template>
  <div class="modal fade modalCustom" id="SmsCode" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-my2" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="text">{{text1}}</div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div v-if="error" class="alert alert-warning" role="alert">{{text4}}</div>
          <div v-if="suc" class="alert alert-success" role="alert">{{text5}} - {{skidka}} %</div>
          <div class="buttonBlock">
            <div class="itemBtton leftItem">
              <input type="text" class="form-control" :placeholder="text2" v-model="code" />
            </div>
            <div class="itemBtton rightItem">
              <button
                :disabled="disabled"
                class="btn btn-warning"
                @click.prevent="checkCode"
              >{{text3}}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SmsCode",
  props: ["order_id"],
  data() {
    return {
      text1: "",
      text2: "",
      text3: "",
      text4: "",
      text5: "",
      code: "",
      disabled: false
    };
  },
  computed: {
    typeOrder() {
      return this.$store.state.typeOrder;
    },
    id_sms() {
      return this.$store.state.id_sms;
    },
    error() {
      return this.$store.state.ERRORCode;
    },
    suc() {
      return this.$store.state.SUCCCode;
    },
    skidka() {
      return this.$store.state.skidka;
    }
  },
  created() {
    this.text1 = this.$store.state.lang.SmsModal[LanguneThisJs];
    this.text2 = this.$store.state.lang.SmsCode[LanguneThisJs];
    this.text3 = this.$store.state.lang.SmsCodeButton[LanguneThisJs];
    this.text4 = this.$store.state.lang.SmsCodeErrorEnter[LanguneThisJs];
    this.text5 = this.$store.state.lang.PayModalDiscount[LanguneThisJs];
  },
  mounted() {},
  methods: {
    checkCode() {
      this.disabled = true;
      this.$store
        .dispatch("checkCode", {
          cod: this.id_sms,
          codes: this.code,
          ajaxmy: true,
          order_id: this.order_id
        })
        .then(() => {
          if ("table" == this.order_id) {
            this.$root.$emit("UpdateActiveTableMoun");
          } else {
            if (this.typeOrder == "type_billiards") {
              this.$store.dispatch("getTablePrice", this.order_id);
            }
          }
          this.disabled = false;
          this.code = "";
        });
    }
  }
};
</script>

<style scoped lang="scss">
.buttonBlock {
  margin-bottom: 10px;
}
</style>