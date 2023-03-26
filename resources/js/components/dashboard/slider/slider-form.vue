<template>
  <div class="slider-form">
    <div class="modal fade" id="sliderFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="btn-close" aria-label="Close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-4 mb-3">
                  <div class="image">
                    <img v-if="previewImage" class="border-bottom" :src="previewImage" />
                    <img v-else class="border-bottom" src="/images/empty.jpg" />
                    <div class="image-upload">
                      <label class="icon" for="image">
                        <i class="fa fa-camera"></i>
                      </label>
                      <label @click="deleteImage" v-if="uploadedImage" class="icon text-secondary px-2">
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                      </label>
                      <input @change="uploadImage"
                        accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp"
                        type="file" id="image" />
                    </div>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="head">{{ $t("color") }}</label>
                        <input :class="{
                          'is-invalid': v$.color.$error,
                        }" class=" form-control" type="text" v-model="v$.color.$model" />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.color.$errors" :key="error">
                            {{ $t("color") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{ $t("TITLE") }}</label>
                        <input type="text" class="form-control" v-model="v$.title.$model" :class="{
                          'is-invalid': v$.title.$error,
                        }" />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.title.$errors" :key="error">
                            {{ $t("TITLE") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12">
                      <div v-if="external" class="form-group">
                        <label for="exampleInputEmail1">{{ $t("URL") }}</label>
                        <input type="text" class="form-control" v-model="v$.url.$model" :class="{
                          'is-invalid': v$.url.$error,
                        }" />
                        <div class="invalid-feedback">
                          <div v-for="error in v$.url.$errors" :key="error">
                            {{ $t("URL") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                      <div v-if="!external" class="form-group">
                        <label for="sel1">{{ $t("PRODUCT") }}</label>
                        <select :class="{ 'is-invalid': v$.product_id.$error }" v-model="v$.product_id.$model"
                          class="form-control" id="sel1">
                          <option :value="product.id" v-for="product in products" :key="product.id">
                            {{ product.name }}
                          </option>
                        </select>
                        <div class="invalid-feedback">
                          <div v-for="error in v$.product_id.$errors" :key="error">
                            {{ $t("PRODUCT") + " " + $t(error.$validator) }}
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox mr-sm-2">
                          <input id="workflow" name="workflow" class="custom-control-input" type="checkbox"
                            v-model="external" />
                          <label style="position: relative;bottom: 2px;right: 3px;" class="custom-control-label"
                            for="workflow">{{
                              $t("EXTERNAL")
                            }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">
                {{ $t("SUBMIT") }}
              </button>
              <button id="close-button" type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t("CANCEL") }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import sliderClient from "../../../shared/http-clients/admin/slider-client";
import { inject, onMounted, reactive, toRefs, watch } from "@vue/runtime-core";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const slider_store = inject("slider_store");
    const toast = inject("toast");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
      products: [],
    });
    const form = reactive({
      title: "",
      url: "",
      external: true,
      product_id: null,
      color: "#fff",
    });
    const rules = {
      title: { required },
      color: { required },
      product_id: {
        required(value) {
          return !form.external ? value : true;
        },
      },
      url: {
        required(value) {
          return form.external ? value : true;
        },
      },
    };
    const v$ = useVuelidate(rules, form);
    onMounted(() => {
      getProducts();
    })
    //Methods

    function uploadImage(e) {
      const image = e.target.files[0];
      data.uploadedImage = image;
      const reader = new FileReader();
      reader.readAsDataURL(image);
      reader.onload = (e) => {
        data.previewImage = e.target.result;
      };
    }
    function deleteImage() {
      data.uploadedImage = null;
      data.previewImage = props.selectedProduct ? props.selectedProduct.image : "";
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedSlider) {
        if (!data.uploadedImage) {
          toast.error(t("IMAGE") + " " + t("required"));
          return;
        }
        store();
      } else {
        update();
      }
    }
    //Commons
    function getProducts() {
      sliderClient
        .getProducts()
        .then((response) => {
          data.products = response.data;
        })
    }

    function store() {
      sliderClient
        .store(getForm())
        .then((response) => {
          context.emit("created", response.data);
          $("#close-button").click();
          toast.success(t("CREATED_SUCCESSFULLY"));
        })
        .catch((error) => {
        });
    }
    function update() {
      sliderClient
        .update(getForm())
        .then((response) => {
          context.emit("updated", response.data);
          $("#close-button").click();
          toast.success(t("UPDATED_SUCCESSFULLY"));
        })
        .catch((error) => {
        });
    }
    function getForm() {
      let formData = new FormData();
      if (props.selectedSlider) {
        formData.append("id", props.selectedSlider.id);
      }
      formData.append("title", form.title);
      formData.append("color", form.color);
      if (form.url) formData.append("url", form.url);
      if (form.product_id) formData.append("product_id", form.product_id);
      formData.append("external", form.external);
      if (data.uploadedImage) {
        formData.append("image", data.uploadedImage);
      }
      return formData;
    }
    function setForm() {
      v$.value.$reset();
      form.product_id = props.selectedSlider ? props.selectedSlider.product_id : "";
      form.title = props.selectedSlider ? props.selectedSlider.title : "";
      form.color = props.selectedSlider ? props.selectedSlider.color : "#fff";
      form.url = props.selectedSlider ? props.selectedSlider.url : "";
      form.external =
        props.selectedSlider && props.selectedSlider.external ? true : false;
      data.previewImage = props.selectedSlider ? props.selectedSlider.image : "";
      data.uploadedImage = null;
    }
    //Watchers
    watch(
      () => {
        slider_store.onFormShow;
      },
      (value) => {
        setForm();
      },
      { deep: true }
    );
    return {
      ...toRefs(data),
      ...toRefs(form),
      v$,
      uploadImage,
      deleteImage,
      save,
      locale,
    };
  },
  props: ["selectedSlider"],
};
</script>

<style scoped lang="scss">
.slider-form {
  .form-control {
    padding: 10px;
  }

  .form-group {
    margin-bottom: 10px;

    label {
      margin-bottom: 5px;
    }
  }

  .icons {
    i {
      font-size: 20px;
    }

    i:hover {
      cursor: pointer;
    }
  }

  .image {
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
      rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    padding-bottom: 5px;

    img {
      width: 100%;
      height: 150px;
    }

    .image-upload {
      i {
        margin-top: 10px;
        color: #888888;
      }

      .icon {
        &:hover {
          cursor: pointer !important;
        }

        i {
          font-size: 14px;
          position: relative;
        }
      }

      text-align: center;

      input[type="file"] {
        display: none;
      }
    }
  }

  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>
