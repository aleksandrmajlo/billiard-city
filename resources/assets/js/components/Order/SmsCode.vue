<template>
  <div class="modal fade modalCustom" id="SmsCode" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-my2" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="text">{{$t('SmsModal')}}</div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div v-if="error" class="alert alert-warning" role="alert">{{$t('SmsCodeErrorEnter')}}</div>
          <div v-if="suc" class="alert alert-success" role="alert">{{$t('PayModalDiscount')}} - {{skidka}} %</div>
          <div class="buttonBlock">
            <div class="itemBtton leftItem">
              <input type="text" class="form-control" :placeholder="$t('SmsCode')" v-model="code" />
            </div>
            <div class="itemBtton rightItem">
              <button
                :disabled="disabled"
                class="btn btn-warning"
                @click.prevent="checkCode"
              >{{$t('SmsCodeButton')}}</button>
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