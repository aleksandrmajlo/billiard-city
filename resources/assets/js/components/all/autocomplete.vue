<template>
  <div class="autocomplete">
    <input
      type="tel"
      placeholder="0-- --- -- --"
      @input="onChange"
      v-model="search"
      @keydown.down="onArrowDown"
      @keydown.up="onArrowUp"
      @keydown.enter="onEnter"
    />
    <div class="autocomplete_conteer" v-show="isOpen">
      <ul id="autocomplete-results" class="autocomplete-results">
        <li class="loading" v-if="isLoading">Loading results...</li>
        <li
          v-else
          v-for="(result, i) in results"
          :key="i"
          @click="setResult(result)"
          class="autocomplete-result"
          :class="{ 'is-active': i === arrowCounter }"
        >{{ result.phone }}</li>
      </ul>
    </div>
  </div>
</template>
<script>
export default {
  name: "autocomplete",
  props: {
    customers: {
      type: Array,
      required: false,
      default: () => []
    },
    isAsync: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  data() {
    return {
      isOpen: false,
      results: [],
      search: "",
      isLoading: false,
      arrowCounter: 0
    };
  },
  created: function() {
    this.$parent.$on("setSearchPhone", this.setSearch);
  },

  methods: {
    onChange() {
      this.$emit("input", this.search);
      if (this.isAsync) {
        this.isLoading = true;
      } else {
        this.filterResults();
        this.isOpen = true;
      }
    },
    filterResults() {
      this.results = this.customers.filter(item => {
        return item.phone.indexOf(this.search.toLowerCase()) > -1;
      });
    },
    setResult(result) {
      this.search = result.phone;
      this.isOpen = false;
      this.$emit("setCustomer", result.id);
    },
    setSearch(val) {
      this.search = val;
    },
    onArrowDown(evt) {
      if (this.arrowCounter < this.results.length) {
        this.arrowCounter = this.arrowCounter + 1;
      }
    },
    onArrowUp() {
      if (this.arrowCounter > 0) {
        this.arrowCounter = this.arrowCounter - 1;
      }
    },
    onEnter() {
      this.search = this.results[this.arrowCounter];
      this.isOpen = false;
      this.arrowCounter = -1;
    },
    handleClickOutside(evt) {
      if (!this.$el.contains(evt.target)) {
        this.isOpen = false;
        this.arrowCounter = -1;
      }
    }
  },
  watch: {
    search(val) {
      this.$emit("changePhone", val);
    }
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },
  destroyed() {
    document.removeEventListener("click", this.handleClickOutside);
  }
};
</script>

<style lang="scss" scoped>
.autocomplete {
  position: relative;
  .autocomplete_conteer {
    border: 1px solid #eeeeee;
    box-shadow: -1px 1px 3px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    max-height: 254px;
    overflow: hidden;
    overflow-y: auto;
    width: 100%;
    z-index: 100;
    position: absolute;
    left: 0;
    top: 30px;
    background: #ffffff;
    .autocomplete-results {
      padding: 0;
      margin: 0;
    }
    .autocomplete-result {
      list-style: none;
      text-align: left;
      padding: 4px 2px;
      padding-left: 15px;
      cursor: pointer;
    }
    .autocomplete-result.is-active,
    .autocomplete-result:hover {
      background: #ccc;
    }
  }
}
</style>