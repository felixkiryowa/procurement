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
          ><img src="img/logo.svg" class="mr-2" alt="logo"
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
            <a class="nav-link" href="kdjdkjdkdjkd">Login</a>
          </li>
          <li class="nav-item dropdown" v-if="!isRegistration">
            <a class="nav-link" href="/register">Register</a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="/accounts/home">{{ appName }}</a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarTogglerDemo02"
        aria-controls="navbarTogglerDemo02"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li><a class="nav-link" href="/accounts/home">Home</a></li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              Accounting
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/manage/chartofaccounts"
                >Manage Chart of Accounts</a>
              <a class="dropdown-item" href="/add/general/entry">Add General Entry</a>
              <a class="dropdown-item" href="/entire/chart/accounts">Entire Chart Of Accounts</a>
              <a class="dropdown-item" href="/list/entries">All Entries</a>
            </div>
          </li>

          <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">Manage Beneficiaries</a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/manage/beneficiaries"
                >Upload Beneficiaries</a
              >
              <a class="dropdown-item" href="/list/beneficiaries"
                >Beneficiaries List</a
              >
            </div>
          </li>
          <li class="nav-item dropdown"><a class="nav-link dropdown-toggle"
              href="#"
              id="navbarDropdown"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false">Reports</a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="/account/statement"
                >Cash Book</a
              >
              <a class="dropdown-item" href="/items/statement"
                >Item Code Reports</a
              >
              <a class="dropdown-item" href="/account/reports">Accounts Reports</a>
            </div>
              </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown" v-if="user !== null">
            <a
              id="navbarDropdown"
              class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              {{ user.firstname }}
              <i class="fas fa-sign-out-alt"></i>
            </a>

            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" @click="LogOutUser($event)"> Log Out </a>
            </div>
          </li>
        </ul>
      </div>
    </nav> -->
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
      if(result === "register") {
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
