<template>
  <div>
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div
        class="
          text-center
          navbar-brand-wrapper
          d-flex
          align-items-center
          justify-content-center
        "
      >
        <a class="navbar-brand brand-logo mr-5" href="index.html"
          ><img src="/img/logo.svg" class="mr-2" alt="logo"
        /></a>
      </div>
      <div
        class="
          navbar-menu-wrapper
          d-flex
          align-items-center
          justify-content-end
        "
      >
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link" href="/">Login</a>
          </li>
          <li class="nav-item dropdown" v-if="!isRegistration">
            <a class="nav-link" href="/register">Register</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
</template>
<script>
import Helpers from "../../Helpers/Helpers";
import { Link } from "@inertiajs/inertia-vue";
export default {
  name: "NavBarComponent",
  props: {
    appName: String,
    user: Object,
  },

  data() {
    return {
      isRegistration: false,
    };
  },

  methods: {
    LogOutUser(event) {
      event.preventDefault();
      Helpers.logoutUser();
    },

    getCurrentUrl() {
      let currentLocation = window.location.href;
      let result = currentLocation.substr(currentLocation.length - 8);
      if (result === "register") {
        this.isRegistration = true;
      }
    },
  },

  created() {
    this.getCurrentUrl();
  },

  components: {
    Link,
  },
};
</script>
