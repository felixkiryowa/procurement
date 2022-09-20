<template>
  <div class="container-scroller">
    <UserLoggedOnNavBarComponent :app="app" :user="user" />
    <div class="container-fluid page-body-wrapper">
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
                    <div class="card-header">
                      <h4>All Registered {{ company.organisationName }} Procurement Officers</h4>
                    </div>

                    <div class="card-body">
                      <br />
                      <div class="table-responsive">
                        <table
                          id="allCompanyUsers"
                          class="
                            table
                            table-bordered
                            table-sm
                            table-striped
                            table-condensed
                          "
                        >
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Username</th>
                              <th>Email</th>
                              <th>First name</th>
                              <th>Last name</th>
                              <th>Status</th>
                              <th>Created On</th>
                              <th>Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr :key="index" v-for="(user, index) in users">
                              <td>{{ index + 1 }}</td>
                              <td>{{ user.userName }}</td>
                              <td>{{ user.email }}</td>
                              <td>{{ user.firstName }}</td>
                              <td>{{ user.lastName }}</td>
                              <td v-if="user.status === 1">
                                <span class="badge badge-success">Active</span>
                              </td>
                              <td v-if="user.status === 0">
                                <span class="badge badge-danger">Deactive</span>
                              </td>
                              <td>{{ user.created_at | customDate }}</td>
                              <td v-if="user.status === 0">
                                <button
                                  @click="ActivateUser(user)"
                                  class="btn btn-sm btn-success"
                                >
                                  Activate
                                </button>
                              </td>
                              <td v-if="user.status === 1">
                                <button
                                  @click="DeActivateUser(user)"
                                  class="btn btn-sm btn-danger"
                                >
                                  DeActivate
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <br />
                  <br />
                </div>
              </div>
            </div>
          </div>
        </div>
        <h1 hidden>{{ checkIfUserIsIdle }}</h1>
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
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
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
    users: Array,
    company:Object,
  },
  //   mixins: [ValidationHelper],
  data() {
    return {
      fullPage: true,
    };
  },
  mounted() {
    $("#allCompanyUsers").DataTable();
  },
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

    ActivateUser(user) {
      this.showLoader();
      axios
        .get("/activate/user/" + user.id, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "Activating Company User Response",
            text: response.data.message,
          });
          window.location.href = "/manage/all/company/users/"+btoa(user.company_id);
          $("#allCompanies").DataTable();
          this.hideLoader();
        })
        .catch((error) => {
          this.hideLoader();
        });
    },

    DeActivateUser(user) {
      this.showLoader();
      axios
        .get("/deactivate/user/" + user.id, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          Swal.fire({
            icon: "success",
            title: "DeActivating Company User Response",
            text: response.data.message,
          });
          window.location.href = "/manage/all/company/users/"+btoa(user.company_id);
          $("#allCompanies").DataTable();
          this.hideLoader();
        })
        .catch((error) => {
          this.hideLoader();
        });
    },
  },

  created() {
    setTimeout(() => this.hideLoader(), 3000);
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
    //   TabContent,
  },
};
</script>
