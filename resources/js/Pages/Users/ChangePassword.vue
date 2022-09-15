<template>
  <div class="container-scroller">
    <UserLoggedOnNavBarComponent :app="app" :user="user" />
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->

      <!-- partial -->
      <SideBarComponent :user="user" />
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-3"></div>

            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">User Settings</h4>
                  <p class="card-description">Change User Settings</p>
                  <form
                    @submit.prevent="changePass()"
                    role="form"
                    class="forms-sample"
                  >
                    <div class="form-group">
                      <label for="exampleInputPassword1">Password</label>
                      <input
                        type="password"
                        :class="{ 'is-invalid': errors.new_password }"
                        v-model="form.new_password"
                        class="form-control"
                        id="exampleInputPassword1"
                        placeholder="Password"
                      />
                      <div v-if="errors.new_password" class="invalid-feedback">
                        <div v-if="errors.new_password" class="error">
                          {{
                            errors.new_password ? errors.new_password[0] : ""
                          }}
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputConfirmPassword1"
                        >Confirm Password</label
                      >
                      <input
                        type="password"
                        :class="{ 'is-invalid': errors.new_confirm_password }"
                        v-model="form.new_confirm_password"
                        class="form-control"
                        id="exampleInputConfirmPassword1"
                        placeholder="Password"
                      />
                      <div
                        v-if="errors.new_confirm_password"
                        class="invalid-feedback"
                      >
                        <div v-if="errors.new_confirm_password" class="error">
                          {{
                            errors.new_confirm_password
                              ? errors.new_confirm_password[0]
                              : ""
                          }}
                        </div>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-success float-right">
                      Submit
                    </button>
                    <button class="btn btn-danger">Cancel</button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-lg-3"></div>
          </div>
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
import "datatables.net-dt/js/dataTables.dataTables";
import "datatables.net-dt/css/jquery.dataTables.min.css";
import { mapActions, mapState, mapGetters } from "vuex";
import { required, email, minLength, sameAs } from "vuelidate/lib/validators";
import { validationMixin } from "vuelidate";

export default {
  props: {
    // user: {},
  },
  data() {
    return {
      fullPage: true,
      errors: [],
      form: new Form({
        new_password: "",
        new_confirm_password: "",
      }),
    };
  },

  mounted() {
    $("#allUsers").DataTable();
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
    checkIfUserIsIdle() {
      return this.isAppIdle ? true : false;
    },
    year() {
      return this.$page.props.year;
    },
    ...mapState("registration", ["isLoading"]),
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),

    changePass() {
      this.$Progress.fail();
      this.showLoader();

      this.form
        .post("/store/password")
        .then((response) => {
          if (response.data.isvalid == false) {
            this.errors = response.data.errors;
          } else {
            Swal.fire({
              icon: "success",
              title: "Changed Password",
              text: response.data.message,
            });

            this.hideLoader();
            this.form.reset();
            window.location.href = "/change/password";
          }
        })
        .catch((error) => {
          this.$Progress.fail();
          this.hideLoader();
        });
    },
  },

  created() {
    setTimeout(() => {
      this.hideLoader();
    }, 2000);
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
  },
};
</script>
