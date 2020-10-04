<template>
  <form>
    <div class="row">
      <div class="col-sm-4 col-xs-6 col-xs-xs-xs-12">
        <label>{{$t('client')}}</label>
      </div>
      <div class="col-sm-8 col-xs-6 col-xs-xs-xs-12 selectFormConteer">
        <model-select :options="customers" v-model="customer_id"></model-select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 col-xs-6 col-xs-xs-xs-12">
        <label>{{$t('worker')}}</label>
      </div>
      <div class="col-sm-8 col-xs-6 col-xs-xs-xs-12 selectFormConteer">
        <model-select :options="users" v-model="user_id"></model-select>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 col-xs-6 col-xs-xs-xs-12">
        <label>{{$t('data_start')}}</label>
      </div>
      <div class="col-xs-6 col-sm-5 col-xs-xs-xs-12">
        <div class="dateConteer">
          <input type="date" v-model="start_date" />
          <input type="time" v-model="start_time" />
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 col-xs-6 col-xs-xs-xs-12">
        <label>{{$t('data_end')}}</label>
      </div>
      <div class="col-xs-6 col-sm-5 col-xs-xs-xs-12">
        <div class="dateConteer">
          <input type="date" v-model="end_date" />
          <input type="time" v-model="end_time" />
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-4 col-xs-6 col-xs-xs-xs-12">
        <label>{{$t('summa')}}</label>
      </div>
      <div class="col-sm-3 col-xs-6 col-xs-xs-xs-12">
        <input type="number" min="0" v-model="amount" placeholder="934 грн" />
      </div>
    </div>

    <div class="row">
      <div class="col-sm-4 col-xs-6 col-xs-xs-xs-12">
        <label>{{$t('PayModaType')}}</label>
      </div>
      <div class="col-sm-3 col-xs-6 col-xs-xs-xs-12">
        <select class="billing" v-model="billing">
          <option value="1">{{$t('PayModalNal')}}</option>
          <option value="2">{{$t('CartModalPay')}}</option>
          <option value="3">{{$t('SertModalPay')}}</option>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-4 col-xs-6 col-xs-xs-xs-12">
        <label>{{$t('comment')}}</label>
      </div>
      <div class="col-sm-8 col-xs-6 col-xs-xs-xs-12">
        <input type="text" v-model="info" placeholder />
      </div>
      <div class="col-xs-12 saves">
        <input
          class="save"
          @click.prevent="submit"
          :disabled="disabled"
          type="submit"
          :value="$t('save')"
        />
        <input @click.prevent="hideModal" class="cancel" type="submit" :value="$t('cancel')" />
      </div>
    </div>
  </form>
</template>

<script>
import { ModelSelect } from "vue-search-select";
export default {
  name: "ReadOrder",
  data() {
    return {
      customers: [],
      customer_id: "",
      users: [],
      user_id: "",
      billing: "",

      start_date: "",
      start_time: "",
      end_date: "",
      end_time: "",
      amount: 0,
      info: "",
      disabled: false,
    };
  },
  props: ["id"],
  components: {
    ModelSelect,
  },
  created() {
    axios
      .post("/order/GetUsers", { name: "bar" })
      .then((response) => {
        this.customers = response.data.users;
      })
      .catch((error) => {})
      .finally(function () {});
    axios
      .get("/order/InfoOrders", { params: { id: this.id } })
      .then((response) => {
        this.users = response.data.users;
        this.user_id = parseInt(response.data.order.user_id);
        if (response.data.order.customer_id) {
          this.customer_id = parseInt(response.data.order.customer_id);
        }
        this.start_date = response.data.order.date.start_date;
        this.start_time = response.data.order.date.start_time;

        this.end_date = response.data.order.date.end_date;
        this.end_time = response.data.order.date.end_time;

        this.info = response.data.order.info;
        this.amount = response.data.order.amount;
        this.billing = response.data.order.billing;
      })
      .catch((error) => {})
      .finally(function () {});
  },
  methods: {
    hideModal() {
      $("#win1").removeClass("target");
    },
    submit() {
      this.disabled = true;
      axios
        .post("/order/ReadOrders", {
          id: this.id,
          user_id: this.user_id,
          customer_id: this.customer_id,
          start_date: this.start_date,
          start_time: this.start_time,
          end_date: this.end_date,
          end_time: this.end_time,
          amount: this.amount,
          info: this.info,
          billing: this.billing,
        })
        .then((response) => {
          if (response.data.suc) {
            $("#win1").removeClass("target");
            setTimeout(() => {
              this.showShwal("success", this.$t("success_order"));
            }, 300);
            setTimeout(() => {
              location.reload();
            }, 2300);
          }
        })
        .catch((error) => {
          this.showShwal("error", this.$t("error"));
        })
        .then(() => {
          this.disabled = false;
        });
    },
  },
};
</script>

<style >
  .billing{
    width: 100%;
    height: 30px;
    background: rgb(255 255 255);
    border-radius: 50px;
    border: none;
    margin-bottom: 10px;
    padding-left: 20px;
    box-sizing: border-box;
  }
</style>
