<template>
  <div class="order-error-container">
    <div class="order-error">
      <i class="fa fa-exclamation-triangle border text-danger" aria-hidden="true"></i>
      <h1 class="text-danger">{{ $t("FAILD") }}</h1>
      <p class="text-secondary">{{ $t("PAYMENT_FAILED") }}</p>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.order-error-container {
  .order-error {
    display: flex;
    height: 300px;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    i {
      font-size: 25px;
      padding: 20px;
      border-radius: 50%;
    }
  }
}
</style>
