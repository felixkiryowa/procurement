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
                  <h4 class="card-title">Procurement Plans List</h4>
                  <p class="card-description float-lg-right">
                    <button
                      type="button"
                      class="btn btn-info btn-rounded btn-fw"
                      @click="openAddPlanModal"
                    >
                      Add New
                    </button>
                  </p>
                  <div class="table-responsive pt-3">
                    <table
                      id="allPlans"
                      class="table table-bordered table-sm table-striped"
                    >
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Title</th>
                          <th>Status</th>
                          <th>Date Created</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr :key="index" v-for="(plan, index) in plans">
                          <td>{{ index + 1 }}</td>
                          <td>{{ plan.title }}  {{ getFormattedDate(plan.financial_year_start) }} - {{ getFormattedDate(plan.financial_year_end) }}</td>
                          <td>
                            <div >
                              <span class="badge badge-warning">{{ plan.status }}</span>
                            </div>
                            
                          </td>
                          <td>
                            {{ new Date(plan.created_at).toLocaleString() }}
                          </td>

                          <td>
                           
                           
                              <button
                                class="btn btn-sm btn-success"
                                @click="ActivateUser(plan)"
                              >
                                Edit
                              </button>
                            
                              |
                          
                              <button
                                class="btn btn-sm btn-danger"
                                @click="PlanDetails(plan)"
                              >
                                View Plan Details
                              </button>
                           
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
          <div class="modal fade" id="createAPlan">
            <div class="modal-dialog">
              <form
                @submit.prevent="
                  editMode ? updateProcurementPlan() : saveProcurementPlan()
                "
                role="form"
              >
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">
                      {{
                        editMode ? bank_account_to_edit : "Create A New Plan"
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
                      <div class="card-header">
                        <div
                                v-if="errors.exists"
                                class="laravel-error"
                              >
                        <div v-if="errors.exists" class="error">
                                  {{
                                    errors.exists
                                      ? errors.exists
                                      : ""
                                  }}
                        </div>
                        </div>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="">Financial Year</label>
                              <select
                                class="form-control"
                                :class="{ 'is-invalid': errors.financialyr }"
                                v-model="form.financialyr"
                              >
                                <option selected value="">Choose Finacial Year</option>
                                <option
                                  v-for="fy in financialyr"
                                  :key="fy"
                                  :value="fy"
                                >
                                  FY {{ fy }}
                                </option>
                              </select>
                              <div
                                v-if="errors.financialyr"
                                class="invalid-feedback"
                              >
                                <div v-if="errors.financialyr" class="error">
                                  {{
                                    errors.financialyr
                                      ? errors.financialyr[0]
                                      : ""
                                  }}
                                </div>
                                
                              </div>
                            </div>
                           
                            <div class="form-group">
                              <label for="email">Status</label>
                              <select
                                class="form-control"
                                v-model="form.status"
                                :class="{ 'is-invalid': errors.status }"
                                >
                                <option selected value="">Choose Status</option>
                                <option value="saved">Saved</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                                </select>
                              <div v-if="errors.status" class="invalid-feedback">
                                <div v-if="errors.status" class="error">
                                  {{ errors.status ? errors.status[0] : "" }}
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
import moment from 'moment';

export default {
  props: {
    users: Array,
    financialyr: Array,
    plans: Array
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      errors: [],
      plan_to_edit: "",
      form: new Form({
        financialyr: "",
        status: "",
      }),
      submitted: false,
    };
  },

  mounted() {
    $("#allPlans").DataTable();
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

    getFormattedDate(date) {
            return moment(date).format("YYYY")
    },

    saveProcurementPlan() {
      this.$Progress.fail();
      this.showLoader();

      this.form
        .post("/create/procurement/plan")
        .then((response) => {
          if (response.data.isvalid == false) {
            this.errors = response.data.errors;
            console.log(this.errors)
          } else {
            Swal.fire({
              icon: "success",
              title: "Added New Plan",
              text: response.data.message,
            });

            this.hideLoader();
            $("#createAPlan").modal("hide");
            this.form.reset();
            window.location.href = "/manage/procurement/plans";
            $("#allPlans").DataTable();
          }
        })
        .catch((response) => {
            this.errors = response.data.errors;

            Swal.fire({
              icon: "success",
              title: "Added New Plan",
              text: response.data.errors,
            });
        });
    },


    PlanDetails(plan){

        console.log(plan)
        window.location.href = "/manage/procurement_plan/details/"+plan.id;

    },

    //Open Modal
    openAddPlanModal() {
      this.editMode = false;
      this.form.reset();
      $("#createAPlan").modal("show");
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
