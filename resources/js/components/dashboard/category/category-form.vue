<template>
  <div class="category-form">
    <div
      class="modal fade"
      id="categoryFormModal"
      tabindex="-1"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="save" enctype="multipart/form-data">
            <div class="modal-header">
              <h5 class="modal-title text-secondary" id="exampleModalLabel">
                {{ $t("FORM") + " " + $t("CATEGORY") }}
              </h5>
              <button
                type="button"
                class="btn-close"
                aria-label="Close"
                data-dismiss="modal"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6 mb-3">
                  <div class="image">
                    <img
                      v-if="previewImage"
                      class="border-bottom"
                      :src="previewImage"
                    />
                    <img
                      v-else
                      class="border-bottom"
                      src="/images/empty.jpg"
                    />
                    <div class="image-upload">
                      <label class="icon" for="image">
                        <i class="fa fa-camera"></i>
                      </label>
                      <label
                        @click="deleteImage"
                        v-if="uploadedImage"
                        class="icon text-secondary px-2"
                      >
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                      </label>
                      <input
                        @change="uploadImage"
                        accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp"
                        type="file"
                        id="image"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label for="exampleInputEmail1">{{
                          $t("NAME")
                        }}</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="v$.name.$model"
                          :class="{
                            'is-invalid': v$.name.$error || nameExist,
                          }"
                        />
                        <div class="invalid-feedback">
                          <div
                            v-for="error in v$.name.$errors"
                            :key="error"
                          >
                            {{ $t("NAME") + " " + $t(error.$validator) }}
                          </div>
                          <div v-if="!v$.name.$invalid && nameExist">
                            {{ $t("NAME") + " " + $t("EXIST") }}
                          </div>
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
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
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
import { required, helpers } from "@vuelidate/validators";
import categoryClient from "../../../shared/http-clients/admin/category-client";
import { computed, inject, reactive, toRefs, watch } from "vue-demi";
import { useI18n } from "vue-i18n";
export default {
  setup(props, context) {
    const { t, locale } = useI18n({ useScope: "global" });
    const category_store = inject("category_store");
    const toast = inject("toast");
    const data = reactive({
      uploadedImage: null,
      previewImage: "",
      nameExist: false,
    });
    const form = reactive({
      name: "",
    });
    const rules = {
      name: { required },
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
      data.previewImage = props.selectedCategory ? props.selectedCategory.image : "";
    }
    function save() {
      if (v$.value.$invalid) {
        v$.value.$touch();
        return;
      }
      if (!props.selectedCategory) {
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
    function store() {
      data.nameExist = false;
      categoryClient
        .store(getForm())
        .then((response) => {
          toast.success(t("CREATED_SUCCESSFULLY"));
          context.emit("created", response.data);
          $("#categoryFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.name
            ? true
            : false;
        });
    }
    function update() {
      data.nameExist = false;
      categoryClient
        .update(getForm())
        .then((response) => {
          toast.success(t("UPDATED_SUCCESSFULLY"));
          context.emit("updated", response.data);
          $("#categoryFormModal").modal("hide");
        })
        .catch((error) => {
          data.nameExist = error.response.data.errors.title_ar
            ? true
            : false;
        });
    }
    function getForm() {
      let formData = new FormData();
      if (props.selectedCategory) {
        formData.append("id", props.selectedCategory.id);
      }
      formData.append("name", form.name);
      if (data.uploadedImage) {
        formData.append("image", data.uploadedImage);
      }
      return formData;
    }
    function setForm() {
      v$.value.$reset();
      form.name = props.selectedCategory ? props.selectedCategory.name : "";
      data.previewImage = props.selectedCategory ? props.selectedCategory.image : "";
      data.uploadedImage = null;
      data.nameExist = false;
    }
    //Watchers
    watch(
      () => {
        category_store.onFormShow;
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
  props: ["selectedCategory"],
};
</script>

<style scoped lang="scss">
.category-form {
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