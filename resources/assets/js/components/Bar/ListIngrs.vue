<template>
  <div class="user_table" v-cloak>
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
    ></loading>
    <table v-cloak class="tableCustomers">
      <tr>
        <td class="td-left">{{ $t("nameTR") }}</td>
        <td>{{ $t("NaSklade") }}</td>
        <td v-if="law"></td>
      </tr>
      <tr v-for="ingredient in ingredients" :key="ingredient.id">
        <td class="td-left">
          {{ ingredient.title }}
        </td>
        <td>
          {{ ingredient.count }}
        </td>
        <td v-if="law">
          <edit-ingr :ingredient="ingredient"></edit-ingr>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
import { eventBus } from "~/app";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";

import EditIngr from "~/components/Bar/EditIngr.vue";

export default {
  name: "ListIngrs",
  props: ["page", "law"],
  components: {
    Loading,
    EditIngr,
  },
  data() {
    return {
      ingredients: [],
      q: "",
      isLoading: false,
      fullPage: true,
    };
  },

  created() {
    this.default();
    eventBus.$on("searchIngredients", (data) => {
      this.q = data.q;
      if (this.q == "") {
        this.default();
        $("ul.pagination").removeClass("hidden");
      } else {
        this.search();
        $("ul.pagination").addClass("hidden");
      }
    });
  },
  methods: {
    default() {
      axios
        .get("/bars/ingredients?page=" + this.page)
        .then((response) => {
          this.ingredients = response.data.ingredients.data;
        })
        .catch((error) => {
          this.showShwal("error", this.$t("error"));
        })
        .then(() => {});
    },
    search() {
      this.isLoading = true;
      axios
        .get("/bars/ingredient/" + this.q)
        .then((response) => {
          this.ingredients = response.data.ingredients;
        })
        .catch((error) => {})
        .then(() => {
          setTimeout(() => {
            this.isLoading = false;
          }, 500);
        });
    },
  },
};
</script>
