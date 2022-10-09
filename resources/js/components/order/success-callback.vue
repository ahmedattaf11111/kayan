<template>
  <div class="order-success-container">
    <div class="order-success">
      <i class="fa fa-exclamation-triangle border text-success" aria-hidden="true"></i>
      <h1 class="text-success">{{ $t("SUCCESS") }}</h1>
      <p class="text-secondary">{{ $t("PAYMENT_SUCCESS") }}</p>
    </div>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
.order-success-container {
  .order-success {
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
