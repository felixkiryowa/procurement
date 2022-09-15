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
        <!-- <a class="navbar-brand brand-logo mr-5" href="#"
          ><img src="/img/logo.svg" class="mr-2" alt="logo"
        /></a> -->
        <h4>{{ app }}</h4>
      </div>
      <div
        class="
          navbar-menu-wrapper
          d-flex
          align-items-center
          justify-content-end
        "
      >
        <button
          class="navbar-toggler navbar-toggler align-self-center"
          type="button"
          data-toggle="minimize"
        >
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              data-toggle="dropdown"
              id="profileDropdown"
            >
              <img src="/img/avarta.png" alt="profile" />
            </a>
            <div
              class="dropdown-menu dropdown-menu-right navbar-dropdown"
              aria-labelledby="profileDropdown"
            >
              <a class="dropdown-item" href="#">
                <i class="ti-user text-primary"></i>
                {{ user.firstName }} {{ user.lastName }}
              </a>
              <a class="dropdown-item" href="#">
                <i class="ti-email text-primary"></i>
                {{ user.email }}
              </a>
              <a class="dropdown-item">
                <i class="ti-briefcase text-primary"></i>
                {{ user.name }}
              </a>
              <a v-if="user.name === 'Procurement Officer'" 
              class="dropdown-item" href="/change/password">
                <i class="ti-settings text-primary"></i>
                User Settings
              </a>
              <a v-if="user.name === 'Provider' || user.name === 'Company'" 
              class="dropdown-item" href="/edit/profile">
                <i class="ti-settings text-primary"></i>
                Organization Settings
              </a>
              <a class="dropdown-item" @click="LogOutUser($event)">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>

            </div>
          </li>
        </ul>
        <button
          class="
            navbar-toggler navbar-toggler-right
            d-lg-none
            align-self-center
          "
          type="button"
          data-toggle="offcanvas"
        >
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
  </div>
</template>
  <script>
import Helpers from "../../Helpers/Helpers";
import { Link } from "@inertiajs/inertia-vue";
export default {
  name: "UserLoggedOnNavBarComponent",
  props: {
    app: String,
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
  },

  created() {

  },

  components: {
    Link,
  },
};
</script>
