<template>
  <div
    class="spinner is-active"
    role="status"
    style="
      float: none;
      width: auto;
      height: auto;
      padding: 10px 0 10px 50px;
      background-position: 20px 0;
    "
  ></div>
</template>

<script>
export default {
  props: {
    isLoading: {
      type: Boolean,
      required: false,
    },
  },
};
</script>

<style scoped>
</style>
