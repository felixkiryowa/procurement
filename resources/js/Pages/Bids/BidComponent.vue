<template>
    <div class="container-scroller">
      <UserLoggedOnNavBarComponent :app="app"  :user="user" />
      <div class="container-fluid page-body-wrapper">
        <!-- partial -->
        <SideBarComponent :user="user"  />
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="row">
                  <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Welcome {{ user.firstName }} {{ user.lastName }}</h3>
                    <h6 class="font-weight-normal mb-0">
                      All systems are running smoothly! You have
  
                    </h6>
                  </div>
                   
                </div>
              </div>
            </div>
            <h1 hidden>{{ checkIfUserIsIdle }}</h1>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
           <FooterComponent :year="year" />
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
    </div>
  </template>
  
    <script>
  import UserLoggedOnNavBarComponent from "../NavBar/UserLoggedOnNavBarComponent.vue";
  import SideBarComponent from "../SideBar/SideBarComponent.vue";
  import FooterComponent from "../Footer/FooterComponent.vue";
  import Loading from "vue-loading-overlay";
  import { mapActions, mapState, mapGetters } from "vuex";
  //   import {
  //     required,
  //     email,
  //     numeric,
  //     minLength,
  //     sameAs,
  //   } from "vuelidate/lib/validators";
  //   import { FormWizard, TabContent, ValidationHelper } from "vue-step-wizard";
  
  export default {
    props: {},
    //   mixins: [ValidationHelper],
    data() {
      return {
        fullPage: true,
      };
    },
    mounted() {},
    computed: {
      ...mapState("registration", ["isLoading"]),
      //   ...mapGetters("registration", ["allCountries"]),
  
      user() {
        return this.$page.props.auth.user;
      },
      app() {
        return this.$page.props.appName;
      },
      year() {
         return this.$page.props.year;
      },
      checkIfUserIsIdle() {
        return this.isAppIdle ? true : false;
      },
    },
  
    methods: {
      ...mapActions("registration", ["showLoader", "hideLoader"]),
    },
  
    created() {},
  
    components: {
      UserLoggedOnNavBarComponent,
      SideBarComponent,
      Loading,
      FooterComponent,
      //   TabContent,
    },
  };
  </script>
  