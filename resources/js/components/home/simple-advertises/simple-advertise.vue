<template>
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
</template>

<script>
import FirstThreeSimpleAdvertises from "./first-three-advertises";
import FourthSimpleAdvertise from "./fourth-advertise";
import FifthSimpleAdvertise from "./fifth-advertise";
import SixthSimpleAdvertise from "./sixth-advertise";
import SeventhSimpleAdvertise from "./seventh-advertise";
import { inject, onMounted, reactive, toRefs } from "vue-demi";
import homeClient from "../../../shared/http-clients/home-client";
export default {
  components: {
    FirstThreeSimpleAdvertises,
    FourthSimpleAdvertise,
    FifthSimpleAdvertise,
    SixthSimpleAdvertise,
    SeventhSimpleAdvertise,
  },
  setup() {
    const store = inject("store");
    const data = reactive({
      simpleAdvertises: [],
    });
    onMounted(() => {
      getSimpleAdvertises();
    });
    //Commons
    function getSimpleAdvertises() {
      homeClient.getSimpleAdvertises().then((response) => {
        data.simpleAdvertises = response.data;
      });
    }
    return { ...toRefs(data) };
  },
};
</script>

<style></style>
