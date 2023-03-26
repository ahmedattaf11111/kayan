<template>
  <div class="product-form">
    <div class="modal fade" id="productFormModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("FORM") + " " + $t("PRODUCT") }}
              </h5>
              <button type="button" class="btn-close" aria-label="Close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6 mb-3">
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
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("NAME")
                      }}</label>
                      <input type="text" class="form-control" v-model="v$.name.$model" :class="{
                        'is-invalid': v$.name.$error || nameExist,
                      }" />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.name.$errors" :key="error">
                          {{ $t("NAME") + " " + $t(error.$validator) }}
                        </div>
                        <div v-if="!v$.name.$invalid && nameExist">
                          {{ $t("NAME") + " " + $t("EXIST") }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="inputState">{{ $t("CATEGORY") }}</label>
                      <select :class="{
                        'is-invalid': v$.category_id.$error,
                      }" v-model="v$.category_id.$model" class="form-control">
                        <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                      </select>
                      <div class="invalid-feedback">
                        <div v-for="error in v$.category_id.$errors" :key="error">
                          {{ $t("CATEGORY") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="inputState">{{ $t("PHARMACIST_FORMS") }}</label>
                      <select :class="{
                        'is-invalid': v$.pharmacist_form_id.$error,
                      }" v-model="v$.pharmacist_form_id.$model" class="form-control">
                        <option v-for="pharmacistForm in pharmacistForms" :value="pharmacistForm.id">{{
                          pharmacistForm.name
                        }}
                        </option>
                      </select>
                      <div class="invalid-feedback">
                        <div v-for="error in v$.pharmacist_form_id.$errors" :key="error">
                          {{ $t("PHARMACIST_FORMS") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="inputState">{{ $t("COMPANY") }}</label>
                      <select :class="{
                        'is-invalid': v$.company_id.$error,
                      }" v-model="v$.company_id.$model" class="form-control">
                        <option v-for="company in companies" :value="company.id">{{
                          company.name
                        }}
                        </option>
                      </select>
                      <div class="invalid-feedback">
                        <div v-for="error in v$.company_id.$errors" :key="error">
                          {{ $t("COMPANY") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("effective_material")
                      }}</label>
                      <input type="text" class="form-control" v-model="v$.effective_material.$model" :class="{
                        'is-invalid': v$.effective_material.$error,
                      }" />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.effective_material.$errors" :key="error">
                          {{ $t("effective_material") + " " + $t(error.$validator) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">{{
                        $t("public_price")
                      }}</label>
                      <input type="number" class="form-control" v-model="v$.public_price.$model" :class="{
                        'is-invalid': v$.public_price.$error,
                      }" />
                      <div class="invalid-feedback">
                        <div v-for="error in v$.public_price.$errors" :key="error">
                          {{ $t("public_price") + " " + $t(error.$validator,{value:1}) }}
                        </div>
                      </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-danger">
                {{ $t("SUBMIT") }}
              </button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t("CLOSE") }}
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
import { required,minValue, helpers } from "@vuelidate/validators";
import productClient from "../../../shared/http-clients/admin/product-client";
import { computed, inject, reactive, watch, toRefs, onMounted } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const product_store = inject("product_store");
    const toast = inject("toast");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
      nameExist: false,
      pharmacistForms: [],
      categories: [],
      companies: []
    });
    const form = reactive({
      name: "",
      category_id: "",
      effective_material: "",
      pharmacist_form_id: "",
      company_id: "",
      public_price: 1,
    });
    const rules = {
      name: { required },
      category_id: { required },
      company_id: { required },
      effective_material: { required },
      pharmacist_form_id: { required },
      public_price: { required,minValue:minValue(1) },
    };
    const v$ = useVuelidate(rules, form);
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
      if (!props.selectedProduct) {
        if (!data.uploadedImage) {
          toast.error(t("IMAGE") + " " + t("required"));
          return;
        }
        store();
      } else {
        update();
      }
    }
    onMounted(() => {
      getGategories();
      getCompanies();
      getPharmacistForms();
    })
    //Commons
    function store() {
      data.nameExist = false;
      productClient
        .store(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", response.data);
          $("#productFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name
            ? true
            : false;
        });
    }
    function update() {
      data.nameExist = false;
      productClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", response.data);
          $("#productFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name
            ? true
            : false;

        });
    }
    function getGategories() {
      productClient
        .getCategories()
        .then((response) => {
          data.categories = response.data;
        })
    }
    function getCompanies() {
      productClient
        .getCompanies()
        .then((response) => {
          data.companies = response.data;
        })
    }
    function getPharmacistForms() {
      productClient
        .getPharmacistForms()
        .then((response) => {
          data.pharmacistForms = response.data;
        })
    }
    function getForm() {
      let formData = new FormData();
      if (props.selectedProduct) {
        formData.append("id", props.selectedProduct.id);
      }
      formData.append("name", form.name);
      formData.append("pharmacist_form_id", form.pharmacist_form_id);
      formData.append("effective_material", form.effective_material);
      formData.append("public_price", form.public_price);
      formData.append("category_id", form.category_id);
      formData.append("company_id", form.company_id);
      if (data.uploadedImage) {
        formData.append("image", data.uploadedImage);
      }
      return formData;
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedProduct ? props.selectedProduct.name : "";
      form.company_id = props.selectedProduct ? props.selectedProduct.company_id : "";
      form.category_id = props.selectedProduct ? props.selectedProduct.category_id : "";
      form.effective_material = props.selectedProduct ? props.selectedProduct.effective_material : "";
      form.pharmacist_form_id = props.selectedProduct ? props.selectedProduct.pharmacist_form_id : "";
      form.public_price = props.selectedProduct ? props.selectedProduct.public_price : "";
      data.previewImage = props.selectedProduct ? props.selectedProduct.image : "";
      data.uploadedImage = null;
      data.nameExist = false;
    }
    //Watchers
    watch(
      () => {
        product_store.onFormShow;
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
    };
  },
  props: ["selectedProduct"],
};
</script>

<style scoped lang="scss">
.product-form {
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
        margin-top: 7px;
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

  .increments {
    font-size: 16px !important;
    justify-content: center;
    display: flex;
    width: 37px;
    text-align: center;
    background-color: #f8f9fa;
    border-radius: 5px;
    align-items: center;
  }

  .modal-footer {
    button {
      width: 80px;
    }
  }
}
</style>