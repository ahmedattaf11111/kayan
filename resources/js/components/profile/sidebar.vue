<template>
  <div class="sidebar mb-3 border">
    <div class="header border-bottom"></div>
    <div class="user">
      <img v-if="!currentUser.image" class="border" src="/assets/img/empty-image.png" />
      <img
        v-if="currentUser.image"
        class="border"
        :src="`/uploads/${currentUser.image}`"
      />
      <form>
        <div class="image-upload">
          <input
            accept="image/png, image/gif, image/jpeg"
            id="file"
            type="file"
            @change="updateProfileImage"
          />
          <label class="icon" for="file">
            <i class="fa fa-camera text-secondary"></i>
          </label>
          <label @click="deleteProfileImage" v-if="currentUser.image" class="icon text-danger px-2">
            <i class="fa fa-close"></i>
          </label>
        </div>
      </form>
      <div class="info text-center">
        <h5>{{ currentUser.name }}</h5>
        <p class="text-mute">{{ currentUser.email }}</p>
      </div>
    </div>
    <ul class="navigation">
      <router-link to="/profile-basic">
        <li :class="{ active: activePath == 'basic' }" class="border">
          {{ $t("BASIC_INFORMATION") }}
        </li>
      </router-link>
      <router-link to="/profile-orders">
        <li :class="{ active: activePath == 'orders' }" class="border">
          {{ $t("ORDERS") }}
        </li>
      </router-link>
    </ul>
  </div>
</template>

<script>
import { inject, reactive, toRefs } from "vue-demi";
import authClient from "../../shared/http-clients/auth-client";
import { useI18n } from "vue-i18n";
export default {
  setup() {
    const store = inject("store");
    const toast = inject("toast");
    const { t, locale } = useI18n({});
    //Methods
    function updateProfileImage(e) {
      store.showLoader = true;
      authClient.updateProfileImage(getForm(e.target.files[0])).then((response) => {
        store.showLoader = false;
        store.currentUser.image = response.data.image;
        toast.success(t("UPDATED_SUCCESSFULLY"));
      });
    }
    function deleteProfileImage() {
      store.showLoader = true;
      authClient.deleteProfileImage().then((response) => {
        store.showLoader = false;
        store.currentUser.image = null;
        toast.success(t("DELETED_SUCCESSFULLY"));
      });
    }
    //Commons
    function getForm(imageFile) {
      let form = new FormData();
      form.append("image", imageFile);
      return form;
    }
    return { ...toRefs(store), updateProfileImage,deleteProfileImage };
  },
  props: ["activePath"],
};
</script>

<style scoped lang="scss">
.sidebar {
  border-radius: 5px;
  .user {
    position: relative;
    flex-direction: column;
    display: flex;
    align-items: center;
    bottom: 46px;
    img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
    }
    .info {
      h5 {
        margin: 15px 0 0 0;
      }
    }
  }
  .header {
    height: 100px;
  }
  .navigation {
    position: relative;
    bottom: 25px;
    list-style: none;
    padding: 0 10px;
    li {
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 10px;
      &:hover,
      &.active {
        background-color: #eeeeee;
        cursor: pointer;
      }
    }
  }
  .image-upload {
    input[type="file"] {
      display: none;
    }
    .icon {
      &:hover {
        cursor: pointer;
      }
      i {
        font-size: 14px;
        position: relative;
        top: 1px;
      }
    }
  }
}
</style>
