<template>
  <div class="win" id="readOrderWin">
    <div class="form-user">
      <div class="row">
        <div class="col-xs-8 col-sm-6 col-sm-push-6">
          <p class="p">
            <span v-if="edit" class="h4">
              {{ $t("ingEdit") }}
            </span>
            <span v-else class="h4">
              {{ $t("ingNew") }}
            </span>
          </p>
        </div>
        <div class="col-xs-4 col-sm-6 col-sm-pull-6" v-show="edit">
          ID:{{ id }}
        </div>
      </div>
      <form>
        <div class="row">
          <div class="col-xs-4 col-xs-xs-xs-6">
            <label>{{ $t("title_ing") }}</label>
          </div>
          <div class="col-xs-8 col-xs-xs-xs-6">
            <input
              type="text"
              v-model="title"
              :placeholder="$t('title_ing_pl')"
            />
          </div>
          <div class="col-xs-4 col-xs-xs-xs-6">
            <label>{{ $t("count_ing") }}</label>
          </div>
          <div class="col-xs-8 col-xs-xs-xs-6">
            <input
              type="number"
              min="0"
              step="0.01"
              v-model="count"
              :placeholder="$t('count_ing_pl')"
            />
          </div>
          <div class="col-xs-12 saves text-warning" v-show="isError">
            {{ $t("change_info") }}
          </div>
          <div class="col-xs-12 saves">
            <div class="user_top">
              <a class="dell" @click.prevent="delet" href="#">
                {{ $t("customersDelete") }}
              </a>
              <a class="edit" :disabled="disabled" @click.prevent="send">{{
                $t("save")
              }}</a>
            </div>
          </div>
        </div>
      </form>
    </div>
    <a class="close" id="closeCompareActWin" title="Закрити" href="#close "></a>
  </div>
</template>
<script>
import { eventBus } from "~/app";

export default {
  name: "FormIngr",

  data() {
    return {
      id: "",
      title: "",
      count: "",
      isError: false,
      disabled: false,

      edit: false,
    };
  },
  created() {
    eventBus.$on("ShowFormIngr", (data) => {
      if (data.newAdd) {
        this.edit = false;
      } else {
        this.id = data.ingredient.id;
        this.title = data.ingredient.title;
        this.count = data.ingredient.count;
        this.edit = true;
      }

      $(".overlayDoc").addClass("target");
      $("#readOrderWin").addClass("target");
    });
  },
  methods: {
    send() {
      if (this.title == "" || this.count == "") {
        this.isError = true;
      } else {
        this.disabled = true;
        this.isError = false;
        if (this.edit) {
          axios
            .post("/bars/ingredients/" + this.id, {
              data: {
                title: this.title,
                count: this.count,
              },
              _method: "patch",
            })
            .then((response) => {
              if (response.data.success) {
                this.showShwal("success", this.$t("ingEditor"));
                eventBus.$emit("searchIngredients", {
                  q: "",
                });
                this.title = "";
                this.count = "";
                this.id = "";
                this.edit = false;

                $(".overlayDoc").removeClass("target");
                $("#readOrderWin").removeClass("target");
              } else {
                this.showShwal("error", this.$t("error"));
              }
            })
            .catch((error) => {
              this.showShwal("error", this.$t("error"));
            })
            .then(() => {
              this.disabled = false;
            });
        } else {
          axios
            .post("/bars/ingredients", {
              title: this.title,
              count: this.count,
            })
            .then((response) => {
              if (response.data.success) {
                document.getElementById("closeCompareActWin").click();
                this.showShwal("success", this.$t("ingAdd"));
                eventBus.$emit("searchIngredients", {
                  q: "",
                });
                this.title = "";
                this.count = "";
                $(".overlayDoc").removeClass("target");
                $("#readOrderWin").removeClass("target");
              } else {
                this.showShwal("error", this.$t("error"));
              }
            })
            .catch((error) => {
              this.showShwal("error", this.$t("error"));
            })
            .then(() => {
              this.disabled = false;
            });
        }
      }
    },
    delet() {
      axios
        .post("/bars/ingredients/" + this.id, { _method: "delete" })
        .then((response) => {
          if (response.data.success) {
            this.showShwal("success", this.$t("customersDeleteText"));
            eventBus.$emit("searchIngredients", {
              q: "",
            });
            this.title = "";
            this.count = "";
            this.id = "";
            this.edit = false;
            $(".overlayDoc").removeClass("target");
            $("#readOrderWin").removeClass("target");
          } else {
            this.showShwal("error", this.$t("error"));
          }
        })
        .catch((error) => {
          this.showShwal("error", this.$t("error"));
        });
    },
  },
};
</script>