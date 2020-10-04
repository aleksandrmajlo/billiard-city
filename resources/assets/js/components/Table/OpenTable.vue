<template>
  <div class="row">
    <div
      v-for="(table,index) in tables"
      v-if="!table.free"
      :class="{ 'active' : table.id == TableCloseActive}"
      class="col-xs-4 board_col"
    >
      <a href @click.prevent="CloseTable(table.id)" class="board_imem item-open">
        <img :src="table.image" alt />
        <p>{{table.name}}</p>
        <span>{{table.number}}</span>
      </a>
    </div>
  </div>
</template>

<script>
export default {
  name: "OpenTable",
  created() {
    this.$store.dispatch("getTables");
  },
  computed: {
    tables() {
      return this.$store.state.tables;
    },
    TableCloseActive() {
      return this.$store.state.TableCloseActive;
    }
  },
  watch: {
    tables() {
      let count = 0;
      this.tables.forEach(el => {
        if (!el.free) count++;
      });
      if (count > 0) {
        $("#opentableTitle").removeClass("hidden");
      } else {
        $("#opentableTitle").addClass("hidden");
      }
    }
  },
  methods: {
    CloseTable(id) {

      $(".ConteerRowTable").addClass("TableOpen");
      $(".ConteerRowTable").removeClass("TableOpenFree");

      this.$store.commit("SetTableactive", id);

    }
  }
};
</script>

