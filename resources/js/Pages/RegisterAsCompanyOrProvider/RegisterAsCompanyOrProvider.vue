<template>
  <div>
    <NavBarComponent :appName="app" :user="user" />
    <br />
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">Beneficiaries Component</div>

            <div class="card-body">I'm a beneficiary component.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import NavBarComponent from "../NavBar/NavBarComponent.vue";
import Loading from "vue-loading-overlay";
import { mapActions, mapState, mapGetters } from "vuex";
export default {
  mounted() {},

  computed: {
    user() {
      return this.$page.props.auth.user;
    },
    app() {
      return this.$page.props.appName;
    },
    checkIfUserIsIdle() {
      return this.isAppIdle ? true : false;
    },
  },

  components: {
    NavBarComponent,
    Loading,
  },
};
</script>
