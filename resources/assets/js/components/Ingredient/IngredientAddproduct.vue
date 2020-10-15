<template>
  <div class="IngredientAddproduct">
    <h2>{{ lang }}</h2>
    <div class="addBlock">
      <a href="#" @click.prevent="add"
        ><i class="fa fa-plus" aria-hidden="true"></i
      ></a>
    </div>
    <div class="itemBlock">
      <div class="item" v-for="(item, index) in items" :key="item.id">
        <div class="form-group">
          <select class="form-control" name="ingredients[]">
            <option
              v-for="(val, name, index) in ings"
              :key="index"
              :selected="name == item.ingredient"
              :value="name"
            >
              {{ val }}
            </option>
          </select>
        </div>
        <div class="form-group">
          <input
            type="number"
            class="form-control"
            min="0"
            step="0.01"
            :value="item.count"
            name="counts[]"
          />
        </div>
        <div class="form-group">
          <a href="#" @click.prevent="remove(index)"
            ><i class="fa fa-remove"></i
          ></a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "IngredientAddproduct",
  data() {
    return {
      counrer: 1,
      ings: [],
      items: [],
    };
  },
  props: ["id", "lang"],
  mounted() {
    console.log(this.id);
    axios
      .post("/ajax/ingredient", { id: this.id })
      .then((response) => {
        if (response.data.success) {
          this.ings = response.data.results.ing;
          response.data.results.ingredients.forEach((el) => {
            this.counter += 1;
            this.items.push({
              id: this.counter,
              ingredient: el.id,
              count: el.count,
            });
          });
          console.log(this.ings);
        }
      })
      .catch(function (error) {
        // setTimeout(() => {
        //   this.showShwal("error", this.$t("error"));
        // }, 1000);
      })
      .finally(function () {});
  },
  methods: {
    add() {
      this.counter += 1;
      this.items.push({
        id: this.counter,
        ingredient: "",
        count: 1,
      });
    },
    remove(index) {
      this.items.splice(index, 1);
    },
  },
  watch: {
    items() {
      if (this.items.length > 0) {
        $("#formCount").addClass("hidden");
      } else {
        $("#formCount").removeClass("hidden");
      }
    },
  },
};
</script>
<style scoped>
.IngredientAddproduct {
  border-bottom: 1px solid;
  border-top: 1px solid;
  margin-bottom: 10px;
  margin-top: 10px;
}
.addBlock {
  margin-bottom: 10px;
}
.addBlock a {
  font-size: 20px;
}
.itemBlock .item {
  display: flex;
  align-items: center;
}
.form-group {
  margin-right: 10px;
}
</style>