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
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-header">
                  <h4>
                    Procurement Plan Details Under Procurement Plan
                    {{ procurement_plan_details.financial_year_start }} -
                    {{ procurement_plan_details.financial_year_end }}
                  </h4>
                </div>
                <div class="card-body">
                  <div class="mb-3 d-flex justify-content-between">
                    <div>
                      <span class="me-3">Created By : </span>
                      <span class="badge badge-success"
                        >{{ procurement_plan_details.firstName }}
                        {{ procurement_plan_details.lastName }}</span
                      >
                    </div>
                    <br />
                    <div>
                      <span class="me-3">Created On : </span>
                      <span class="badge badge-success">{{
                        procurement_plan_details.created_at | customDate
                      }}</span>
                    </div>
                  </div>
                  <div class="mb-3 d-flex justify-content-between">
                    <div>
                      <span class="me-3">Brief Description : </span>
                      <span class="badge badge-success">{{
                        procurement_plan_details.brief
                      }}</span>
                    </div>
                  </div>
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <td colspan="2">Amount</td>
                        <td class="text-end">
                          {{ procurement_plan_details.amount | formatNumber }}
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">Category</td>
                        <td class="text-end">
                          <span class="badge badge-danger">{{
                            procurement_plan_details.category_name
                          }}</span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">Method</td>
                        <td class="text-danger text-end">
                          <span class="badge badge-danger">{{
                            procurement_plan_details.method_name
                          }}</span>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="2">Plan Detail Approval Status</td>
                        <td class="text-danger text-end">
                          <span
                            class="badge badge-success"
                            v-if="
                              procurement_plan_details.status === 'approved'
                            "
                            >{{ procurement_plan_details.status }}</span
                          >
                          <span
                            class="badge badge-warning"
                            v-if="
                              procurement_plan_details.status !== 'approved'
                            "
                            >{{ procurement_plan_details.status }}</span
                          >
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div
                    v-if="
                      logged_in_user_step === procurement_plan_details.step &&
                      isLoggedInUserApprover &&
                      procurement_plan_details.status !== 'approved'
                    "
                  >
                    <form @submit.prevent="approveProcurementPlanDetail">
                      <div class="form-group">
                        <label for="">Select Option</label>
                        <select
                          v-model="form2.status"
                          class="form-control"
                          :class="{
                            'is-invalid': form2.errors.has('status'),
                          }"
                        >
                          <option value="">Choose Option</option>
                          <option value="Approved">Approve</option>
                          <option value="Reject">Reject</option>
                        </select>
                        <span
                          class="invalid-feedback"
                          v-if="form2.errors.has('status')"
                          v-html="form2.errors.get('status')"
                        >
                        </span>
                      </div>
                      <div class="form-group">
                        <label for=""
                          >Enter Reason For Approval Or Reject</label
                        >
                        <textarea
                          v-model="form2.reason"
                          class="form-control"
                          :class="{
                            'is-invalid': form2.errors.has('reason'),
                          }"
                          cols="30"
                          rows="4"
                        ></textarea>
                        <span
                          class="invalid-feedback"
                          v-if="form2.errors.has('reason')"
                          v-html="form2.errors.get('reason')"
                        >
                        </span>
                      </div>
                      <div class="float-right">
                        <button type="submit" class="btn btn-success btn-sm">
                          Submit
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- /.Start  User Activation modal-dialog -->

          <!--End User Activation Modal-->
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
    getDetails: Object,
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      errors: [],
      procurement_plan_details: {},
      logged_in_user_step: "",
      isLoggedInUserApprover: false,
      form2: new Form({
        id: "",
        reason: "",
        step: "",
        user_id: "",
        status: "",
        plan_id:"",
      }),
    };
  },

  mounted() {},
  computed: {
    ...mapState("registration", ["isLoading"]),

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
    ...mapState("registration", ["isLoading"]),
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),

    approveProcurementPlanDetail() {
      this.showLoader();
      if (this.form2.status === "Approved") {
        this.form2
          .post("/approve/procurement/plan/detail", {
            headers: {
              "X-Frame-Options": "sameorigin",
              "X-Content-Type-Options": "nosniff",
              "strict-transport-security": "max-age=31536000",
            },
          })
          .then((response) => {
            Swal.fire({
              icon: "success",
              title: "Response",
              text: response.data.message,
            });
            window.location.href = '/manage/procurement_plan/details/'+btoa(this.form2.plan_id);
          })
          .catch((error) => {
            this.hideLoader();
          });
      } else {
        this.form2
          .post("/reject/procurement/plan/detail", {
            headers: {
              "X-Frame-Options": "sameorigin",
              "X-Content-Type-Options": "nosniff",
              "strict-transport-security": "max-age=31536000",
            },
          })
          .then((response) => {
            Swal.fire({
              icon: "success",
              title: "Response",
              text: response.data.message,
            });
            window.location.href = '/manage/procurement_plan/details/'+btoa(this.form2.plan_id);
          })
          .catch((error) => {
            this.hideLoader();
          });
      }
    },

    getProcurementPlanDetails(step) {
      this.showLoader();
      axios
        .get("/get/who/to/approve/step/" + step, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          this.isLoggedInUserApprover = response.data.message;
          this.form2.user_id = response.data.user_id;
          this.logged_in_user_step = response.data.user_step;
          this.hideLoader();
        })
        .catch((error) => {
          this.hideLoader();
        });
    },

  },

  created() {
    this.procurement_plan_details = this.getDetails;
    this.form2.step = this.procurement_plan_details.step;
    this.form2.id = this.procurement_plan_details.id;
    this.form2.plan_id = this.procurement_plan_details.plan_id;
    this.getProcurementPlanDetails(this.procurement_plan_details.step);
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
  },
};
</script>
  