<template>
  <div class="ps-home ps-home--4">
    <div class="ps-home ps-home--4">
      <Category />
      <Slider />
      <div class="ps-home__content">
        <FirstThreeSimpleAdvertises
          v-if="simpleAdvertises.slice(0, 3).length <= 3"
          :simpleAdvertises="simpleAdvertises.slice(0, 3)"
        />
        <FourthSimpleAdvertise
          v-if="simpleAdvertises[3]"
          :fourthSimpleAdvertise="simpleAdvertises[3]"
        />
        <FifthSimpleAdvertise
          v-if="simpleAdvertises[4]"
          :fifthSimpleAdvertise="simpleAdvertises[4]"
        />
        <SixthSimpleAdvertise
          v-if="simpleAdvertises[5]"
          :sixthSimpleAdvertise="simpleAdvertises[5]"
        />
        <SeventhSimpleAdvertise
          v-if="simpleAdvertises[6]"
          :seventhSimpleAdvertise="simpleAdvertises[6]"
        />
        <Newsletter />
      </div>
    </div>
  </div>
</template>
<script>
import Category from "./category";
import Newsletter from "./newsletter";
import Slider from "./slider";
import homeClient from "../../../shared/http-clients/home-client";
import FirstThreeSimpleAdvertises from "./simple-advertises/first-three-advertises";
import FourthSimpleAdvertise from "./simple-advertises/fourth-advertise";
import FifthSimpleAdvertise from "./simple-advertises/fifth-advertise";
import SixthSimpleAdvertise from "./simple-advertises/sixth-advertise";
import SeventhSimpleAdvertise from "./simple-advertises/seventh-advertise";
import { reactive, toRefs } from "vue-demi";
import store from "../../../shared/store";
export default {
  components: {
    Category,
    Slider,
    Newsletter,
    FirstThreeSimpleAdvertises,
    FourthSimpleAdvertise,
    FifthSimpleAdvertise,
    SixthSimpleAdvertise,
    SeventhSimpleAdvertise,
  },
  setup() {
    let data = reactive({
      simpleAdvertises: [],
    });
    created();
    //Commons
    function created() {
      store.showLoader = true;
      homeClient.getSimpleAdvertises().then((response) => {
        store.showLoader = false;
        data.simpleAdvertises = response.data;
      });
    }
    return { ...toRefs(data) };
  },
};
</script>

<style lang="scss">
</style>
