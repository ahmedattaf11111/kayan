<template>
<footer class="ps-footer ps-footer--2 ps-footer--4">
    <div class="container">
      <div class="ps-footer--bottom">
        <div class="row">
          <div class="col-12 col-md-6">
            <p>{{ $t("ALL_RIGHTS_RESERVED") }}</p>
          </div>
          <div class="col-12 col-md-6 text-right">
            <img src="/assets/img/payment.png" alt /><img class="payment-light" src="/assets/img/payment-light.png" alt />
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
import { onMounted, reactive, toRefs } from "vue-demi";
export default {
  setup() {
    const data = reactive({
    });
    onMounted(() => {
    });
    return { ...toRefs(data) };
  },
};
</script>

<style lang="scss" scoped>
.ps-footer__link:hover,
.ps-footer__email:hover {
  cursor: text;
}
</style>
