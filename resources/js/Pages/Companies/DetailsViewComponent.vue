<template>
  <div class="container-scroller">
    <UserLoggedOnNavBarComponent :app="app" :user="user" />
    <div class="container-fluid page-body-wrapper">
      <!-- partial -->
      <SideBarComponent :user="user" />
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="vld-parent">
            <loading
              :active.sync="isLoading"
              :can-cancel="false"
              color="#074578"
              loader="spinner"
              :opacity="0.5"
              :is-full-page="fullPage"
            />
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">
                    Welcome {{ user.firstName }} {{ user.lastName }}
                  </h3>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-body">
                      <br />
                      <div class="container">
                        <div class="team-single">
                          <div class="row">
                            <div
                              class="col-lg-4 col-md-5 xs-margin-30px-bottom"
                            >
                              <div class="team-single-img">
                                <img src="/img/avatar7.png" alt="avarta" />
                              </div>
                              <div
                                class="
                                  bg-light-gray
                                  padding-30px-all
                                  md-padding-25px-all
                                  sm-padding-20px-all
                                  text-center
                                "
                              >
                                <h4
                                  class="
                                    margin-10px-bottom
                                    font-size24
                                    md-font-size22
                                    sm-font-size20
                                    font-weight-600
                                  "
                                >
                                  {{ selected_user.user_role }}
                                </h4>
                                <p class="sm-width-95 sm-margin-auto">
                                  Status :
                                  <span
                                    v-if="selected_user.status === 1"
                                    class="badge badge-success"
                                    >Active</span
                                  >
                                  <span
                                    v-if="selected_user.status === 0"
                                    class="badge badge-danger"
                                    >DeActive</span
                                  >
                                </p>

                                <p>Procurement categories</p>
                                <hr />
                                <div
                                  v-for="(category, index) in categories"
                                  :key="index"
                                >
                                  <span class="badge badge-primary">
                                    {{ category }}
                                  </span>
                                </div>
                              </div>
                            </div>

                            <div class="col-lg-8 col-md-7">
                              <div
                                class="
                                  team-single-text
                                  padding-50px-left
                                  sm-no-padding-left
                                "
                              >
                                <h4
                                  class="
                                    font-size38
                                    sm-font-size32
                                    xs-font-size30
                                  "
                                >
                                  {{ selected_user.organisationName }}
                                </h4>
                                <p class="no-margin-bottom">
                                  {{ selected_user.briefDescription }}
                                </p>
                                <div
                                  class="contact-info-section margin-40px-tb"
                                >
                                  <ul class="list-style9 no-margin">
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i
                                            class="
                                              fas
                                              fa-graduation-cap
                                              text-orange
                                            "
                                          ></i>
                                          <strong
                                            class="margin-10px-left text-orange"
                                            >Names:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>
                                            {{ selected_user.firstName }}
                                            {{ selected_user.lastName }}
                                          </p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i class="far fa-gem text-yellow"></i>
                                          <strong
                                            class="margin-10px-left text-yellow"
                                            >Registration Number:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>{{ selected_user.registrationNumber }}</p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i class="far fa-gem text-yellow"></i>
                                          <strong
                                            class="margin-10px-left text-yellow"
                                            >Tax ID:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>{{ selected_user.taxId }}</p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i
                                            class="far fa-file text-lightred"
                                          ></i>
                                          <strong
                                            class="
                                              margin-10px-left
                                              text-lightred
                                            "
                                            >Country:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>{{ selected_user.country }}</p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i
                                            class="
                                              fas
                                              fa-map-marker-alt
                                              text-green
                                            "
                                          ></i>
                                          <strong
                                            class="margin-10px-left text-green"
                                            >Address:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>{{ selected_user.address}}</p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i
                                            class="
                                              fas
                                              fa-mobile-alt
                                              text-purple
                                            "
                                          ></i>
                                          <strong
                                            class="
                                              margin-10px-left
                                              xs-margin-four-left
                                              text-purple
                                            "
                                            >Phone:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>{{ selected_user.companyPhoneNumber }}</p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i
                                            class="fas fa-envelope text-pink"
                                          ></i>
                                          <strong
                                            class="
                                              margin-10px-left
                                              xs-margin-four-left
                                              text-pink
                                            "
                                            >Email:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>
                                            {{ selected_user.email }}
                                          </p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i
                                            class="fas fa-envelope text-pink"
                                          ></i>
                                          <strong
                                            class="
                                              margin-10px-left
                                              xs-margin-four-left
                                              text-pink
                                            "
                                            >City:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>
                                            {{ selected_user.city }}
                                          </p>
                                        </div>
                                      </div>
                                    </li>
                                    <li>
                                      <div class="row">
                                        <div class="col-md-5 col-5">
                                          <i
                                            class="fas fa-envelope text-pink"
                                          ></i>
                                          <strong
                                            class="
                                              margin-10px-left
                                              xs-margin-four-left
                                              text-pink
                                            "
                                            >Region:</strong
                                          >
                                        </div>
                                        <div class="col-md-7 col-7">
                                          <p>
                                            {{ selected_user.region }}
                                          </p>
                                        </div>
                                      </div>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br />
                  <br />
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
  props: {
    selected_user: Object,
  },
  //   mixins: [ValidationHelper],
  data() {
    return {
      fullPage: true,
      categories: [],
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

  created() {
    setTimeout(() => this.hideLoader(), 3000);
    var myarray = JSON.parse(this.selected_user.procurementCategory).split(",");

    for (var i = 0; i < myarray.length; i++) {
      console.log(myarray[i]);
      this.categories.push(myarray[i])

    }
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
  },
};
</script>
  