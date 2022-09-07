<template>
  <div class="container-scroller">
    <UserLoggedOnNavBarComponent :appName="app" :user="user" />
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->

      <!-- partial -->
      <SideBarComponent />
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Company Users</h4>
                  <p class="card-description float-lg-right">
                    <button
                      type="button"
                      class="btn btn-info btn-rounded btn-fw"
                      @click="openAddUserModal"
                    >
                      Add User
                    </button>
                  </p>
                  <div class="table-responsive pt-3">
                    <table
                      id="allUsers"
                      class="table table-bordered table-sm table-striped"
                    >
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Date Joined</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr :key="index" v-for="(user, index) in users">
                          <td>{{ index + 1 }}</td>
                          <td>{{ user.firstName }}</td>
                          <td>{{ user.lastName }}</td>
                          <td>
                            {{ new Date(user.created_at).toLocaleString() }}
                          </td>
                          <td>
                            <div v-if="user.status === 1">
                              <span class="btn btn-sm btn-warning">Active</span>
                            </div>
                            <div v-else-if="user.status === 0">
                              <span class="btn btn-sm btn-danger"
                                >Disabled</span
                              >
                            </div>
                          </td>
                          <td>
                            <div v-if="user.status === 1">
                              <button
                                class="btn btn-sm btn-success"
                                @click="DeactivateUser(user)"
                              >
                                Deactivate
                              </button>
                            </div>
                            <div v-else-if="user.status === 0">
                              <button
                                class="btn btn-sm btn-success"
                                @click="ActivateUser(user)"
                              >
                                Activate
                              </button>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- /.create a User modal -->
          <div class="modal fade" id="createAuser">
            <div class="modal-dialog">
              <form
                @submit.prevent="
                  editMode ? updateSubSubSubBankAccountBackend() : saveUser()
                "
                role="form"
              >
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">
                      {{
                        editMode ? bank_account_to_edit : "Create A New User"
                      }}
                    </h4>
                    <button
                      type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="card card-primary">
                      <div class="card-header"></div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="">First Name</label>
                              <input
                                class="form-control input-sm"
                                :class="{ 'is-invalid': errors.first_name }"
                                v-model="form.first_name"
                                placeholder="Enter First Name"
                                name="first_name"
                              />
                              <div
                                v-if="errors.first_name"
                                class="invalid-feedback"
                              >
                                <div v-if="errors.first_name" class="error">
                                  {{
                                    errors.first_name
                                      ? errors.first_name[0]
                                      : ""
                                  }}
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="role_name">Last Name</label>
                              <input
                                type="text"
                                class="form-control"
                                :class="{ 'is-invalid': errors.last_name }"
                                name="last_name"
                                v-model="form.last_name"
                                placeholder="Enter Last Name"
                              />

                              <div
                                v-if="errors.last_name"
                                class="invalid-feedback"
                              >
                                <div v-if="errors.last_name" class="error">
                                  {{
                                    errors.last_name ? errors.last_name[0] : ""
                                  }}
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="email">Email</label>
                              <input
                                type="email"
                                class="form-control"
                                :class="{ 'is-invalid': errors.email }"
                                placeholder="Enter Email Address"
                                v-model="form.email"
                              />
                              <div v-if="errors.email" class="invalid-feedback">
                                <div v-if="errors.email" class="error">
                                  {{ errors.email ? errors.email[0] : "" }}
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <label for="email">Password</label>
                              <input
                                type="password"
                                class="form-control"
                                :class="{ 'is-invalid': errors.password }"
                                placeholder="Enter Password"
                                v-model="form.password"
                              />
                              <div
                                v-if="errors.password"
                                class="invalid-feedback"
                              >
                                <div v-if="errors.password" class="error">
                                  {{
                                    errors.password ? errors.password[0] : ""
                                  }}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-danger"
                      data-dismiss="modal"
                    >
                      Close
                    </button>
                    <button type="submit" class="btn btn-success float-right">
                      {{ editMode ? "Update" : "Save" }}
                    </button>
                  </div>
                </div>
              </form>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.End Add User modal-dialog -->

          <!-- /.Start  User Activation modal-dialog -->

          <div class="modal fade" id="activateUser">
            <div class="modal-dialog">
              <form
                @submit.prevent="editMode ? activateUser() : saveUser()"
                role="form"
              >
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">
                      {{ editMode ? user_to_edit : "Create A New User" }}
                    </h4>
                    <button
                      type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="card card-primary">
                      <div class="card-header"></div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-danger"
                      data-dismiss="modal"
                    >
                      Close
                    </button>
                    <button type="submit" class="btn btn-success float-right">
                      {{ editMode ? "Activate User" : "Save" }}
                    </button>
                  </div>
                </div>
              </form>
              <!-- /.modal-content -->
            </div>
            <h1 hidden>{{ checkIfUserIsIdle }}</h1>
            <!-- /.modal-dialog -->
          </div>

          <!--End User Activation Modal-->
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <FooterComponent />
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
    users: Array,
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      errors: [],
      user_to_edit: "",
      form: new Form({
        last_name: "",
        first_name: "",
        email: "",
        password: "",
      }),
      submitted: false,
    };
  },

  mounted() {
    $("#allUsers").DataTable();
  },
  computed: {
    ...mapState("registration", ["isLoading"]),

    user() {
      return this.$page.props.auth.user;
    },
    app() {
      return this.$page.props.appName;
    },
    checkIfUserIsIdle() {
      return this.isAppIdle ? true : false;
    },
    ...mapState("registration", ["isLoading"]),
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),

    saveUser() {
      this.$Progress.fail();
      this.showLoader();

      this.form
        .post("/create/company/user")
        .then((response) => {
          if (response.data.isvalid == false) {
            this.errors = response.data.errors;
          } else {
            Swal.fire({
              icon: "success",
              title: "Added New User",
              text: response.data.message,
            });

            this.hideLoader();
            $("#createAuser").modal("hide");
            this.form.reset();
            window.location.href = "/manage/company/users";
            $("#allUsers").DataTable();
          }
        })
        .catch((error) => {
          this.$Progress.fail();
          this.hideLoader();
        });
    },

    ActivateUser(user) {
      //this.form.fill(user);
      //this.editMode = true;
      console.log(user);
      this.user_to_edit = "Activate " + user.firstName + " " + user.lastName;

      axios
        .get("/activate/company/user/" + user.id)
        .then((response) => {
          if (response.data.isvalid == false) {
            this.errors = response.data.errors;
          } else {
            Swal.fire({
              icon: "success",
              title: "User Activated",
              text: response.data.message,
            });

            this.hideLoader();
            window.location.href = "/manage/company/users";
            $("#allUsers").DataTable();
          }
        })
        .catch((error) => {
          this.hideLoader();
        });

      //$("#activateUser").modal("show");
    },

    DeactivateUser(user) {
      //this.form.fill(user);
      //this.editMode = true;
      console.log(user);
      this.user_to_edit = "Activate " + user.firstName + " " + user.lastName;

      axios
        .get("/deactivate/company/user/" + user.id)
        .then((response) => {
          if (response.data.isvalid == false) {
            this.errors = response.data.errors;
          } else {
            Swal.fire({
              icon: "success",
              title: "User Deactivated",
              text: response.data.message,
            });

            this.hideLoader();
            window.location.href = "/manage/company/users";
            $("#allUsers").DataTable();
          }
        })
        .catch((error) => {
          this.hideLoader();
        });

      //$("#activateUser").modal("show");
    },

    //Open Modal
    openAddUserModal() {
      this.editMode = false;
      this.form.reset();
      $("#createAuser").modal("show");
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
