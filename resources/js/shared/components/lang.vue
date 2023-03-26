<template>
  <div class="lang-container">
    <a href="" v-if="$i18n.locale == 'ar'" @click.prevent="changeLang('en')">
      <img src="/assets/images/english.png" />
    </a>
    <a href="" v-if="$i18n.locale == 'en'" @click.prevent="changeLang('ar')">
      <img src="/assets/images/arabic.png" />
    </a>
  </div>
</template>
<script>
import LangUtil from '../utils/lang-util';
export default {
  setup() {
    const lang = LangUtil.setup();
    function changeLang(locale) {
      lang.changeLang(locale);
    }
    return { changeLang };
  },
};
</script>
<style lang="scss" scoped>
.lang-container {
  img {
    width: 20px;
    height: 15px;
  }
}
</style>