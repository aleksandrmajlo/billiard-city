<template>
  <div class="win" id="readOrderWin">
    <div class="form-user">
      <div class="row">
        <div class="col-xs-8 col-sm-6 col-sm-push-6">
          <p class="p">
            <span v-if="edit" class="h4">
              {{ $t("catEdit") }}
            </span>
            <span v-else class="h4">
              {{ $t("catNew") }}
            </span>
          </p>
        </div>
        <div class="col-xs-4 col-sm-6 col-sm-pull-6" v-show="edit">
          ID:{{ id }}
        </div>
      </div>
      <form enctype="multipart/form-data">
        <div class="row">
          <div class="col-xs-4 col-xs-xs-xs-6">
            <label>{{ $t("title_cat") }}</label>
          </div>
          <div class="col-xs-8 col-xs-xs-xs-6">
            <input
              type="text"
              v-model="title"
              :placeholder="$t('title_ing_pl')"
            />
          </div>

          <div class="col-xs-4 col-xs-xs-xs-6">
            <label>{{ $t("catColir") }}</label>
          </div>
          <div class="col-xs-8 col-xs-xs-xs-6">
            <input type="color" v-model="color" />
          </div>
        </div>
        <div class="row WrapDropzone">
          <div class="col-xs-4 col-xs-xs-xs-6">
            <label>Фото</label>
          </div>
          <div class="col-xs-8 col-xs-xs-xs-6">
            <dropzone-comp
              ref="upload"
              :image="image"
              :edit="edit"
              :title="title"
              v-on:SetImagesChild="SetImages"
            ></dropzone-comp>
          </div>
          <div class="col-xs-12 saves text-warning" v-show="isError">
            {{ $t("change_info") }}
          </div>
        </div>
        <div class="row">
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
import DropzoneComp from "~/components/DropzoneComp.vue";

export default {
  name: "FormCat",
  components: { DropzoneComp },
  data() {
    return {
      id: "",
      title: "",
      color: "",
      image: "",

      isError: false,
      disabled: false,

      edit: false,
    };
  },
  computed: {},
  created() {
    eventBus.$on("ShowFormCat", (data) => {
      if (data.newAdd) {
        this.edit = false;
        this.title = "";
        this.color = "";
        this.image = "";
        this.edit = false;
      } else {
        this.id = data.category.id;
        this.title = data.category.title;
        this.color = data.category.color;
        this.image = data.category.image;
        this.edit = true;
      }
      $(".overlayDoc").addClass("target");
      $("#readOrderWin").addClass("target");
    });
  },
  methods: {
    send() {
      if (this.title == "") {
        this.isError = true;
      } else {
        this.disabled = true;
        this.isError = false;
        if (this.edit) {
          axios
            .post("/bars/categories/" + this.id, {
              data: {
                image: this.image,
                title: this.title,
                color: this.color,
              },
              _method: "patch",
            })
            .then((response) => {
              if (response.data.success) {
                this.showShwal("success", this.$t("catEditor"));
                eventBus.$emit("searchCats", {
                  q: "",
                });
                this.title = "";
                this.color = "";
                this.image = "";
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
            .post("/bars/categories", {
              image: this.image,
              title: this.title,
              color: this.color,
            })
            .then((response) => {
              if (response.data.success) {
                document.getElementById("closeCompareActWin").click();
                this.showShwal("success", this.$t("catAdd"));
                eventBus.$emit("searchCats", {
                  q: "",
                });
                this.title = "";
                this.color = "";
                this.image = "";
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
        .post("/bars/categories/" + this.id, { _method: "delete" })
        .then((response) => {
          if (response.data.success) {
            this.showShwal("success", this.$t("customersDeleteText"));
            eventBus.$emit("searchCats", {
              q: "",
            });
            this.title = "";
            this.color = "";
            this.id = "";
            this.image = "";
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
    SetImages(image) {
      if (image) {
        this.image = image;
      } else {
        this.image = null;
      }
    },
  },
};
</script>
