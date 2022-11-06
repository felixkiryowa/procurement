<template>
    <div class="container-scroller">
      <UserLoggedOnNavBarComponent :appName="app" :user="user" />
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
                  <div class="card-body">
                    <h4 class="card-title">
                      Procurement Plans Details [FY
                      {{ getFormattedDate(plan.financial_year_start) }} -
                      {{ getFormattedDate(plan.financial_year_end) }}]
                    </h4>


                    <form
                  @submit.prevent="
                    editMode
                      ? updateProcurementPlanDetail()
                      : saveProcurementPlanDetail()
                  "
                  role="form"
                >
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">
                        {{
                          editMode ? bank_account_to_edit : "Create Plan Details"
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

                            <div class = "row"
                            >
                            <div class = "col-md-6">
                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="">Select Category</label>
                                  </div>

                                  <div class="col-md-8">
                                    <select
                                      class="form-control"
                                      :class="{
                                        'is-invalid': errors.category_id,
                                      }"
                                      v-model="form.category_id"
                                    >
                                      <option selected value="">
                                        Choose Category
                                      </option>
                                      <option
                                        v-for="cat in getCategories"
                                        :key="cat.id"
                                        :value="cat.id"
                                      >
                                        {{ cat.name }}
                                      </option>
                                    </select>
                                    <div
                                      v-if="errors.category_id"
                                      class="invalid-feedback"
                                    >
                                      <div
                                        v-if="errors.category_id"
                                        class="error"
                                      >
                                        {{
                                          errors.category_id
                                            ? errors.category_id[0]
                                            : ""
                                        }}
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              </div>

                              <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Brief Description</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="text"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.brief }"
                                      name="brief"
                                      v-model="form.brief"
                                      placeholder="Enter Brief Description"
                                    />

                                    <div
                                      v-if="errors.brief"
                                      class="invalid-feedback"
                                    >
                                      <div v-if="errors.brief" class="error">
                                        {{ errors.brief ? errors.brief[0] : "" }}
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

                              </div>

                              </div>

							  <div class= "row">
							  <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="">Select Method</label>
                                  </div>

                                  <div class="col-md-8">
                                    <select
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.method_id }"
                                      v-model="form.method_id"
                                    >
                                      <option selected value="">
                                        Choose Method
                                      </option>
                                      <option
                                        v-for="mtd in getMethods"
                                        :key="mtd.id"
                                        :value="mtd.id"
                                      >
                                        {{ mtd.name }}
                                      </option>
                                    </select>
                                    <div
                                      v-if="errors.method_id"
                                      class="invalid-feedback"
                                    >
                                      <div v-if="errors.method_id" class="error">
                                        {{
                                          errors.method_id
                                            ? errors.method_id[0]
                                            : ""
                                        }}
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

							  </div>

							  <div class="col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name">Estimate in UGX</label>
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="number"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.amount }"
                                      name="amount"
                                      v-model="form.amount"
                                      placeholder="Enter Amount"
                                    />

                                    <div
                                      v-if="errors.amount"
                                      class="invalid-feedback"
                                    >
                                      <div v-if="errors.amount" class="error">
                                        {{
                                          errors.amount ? errors.amount[0] : ""
                                        }}
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>

							  </div>

							  </div>

							  <div class= "row">
							  <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Tender Document/RFP</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.C }"
                                      name="C"
                                      v-model="form.C"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  <div class="col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Bid/EOI Invitation & Open
                                    </label>
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.E }"
                                      name="E"
                                      v-model="form.E"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  </div>

							  <div class= "row">
							  <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Bid/EOI Evaluation/Short List</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.F }"
                                      name="F"
                                      v-model="form.F"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  <div class="col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Issuance of RFP (Services)</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.G }"
                                      name="G"
                                      v-model="form.G"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  </div>

							  <div class= "row">
							  <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Receipt of RFP (Service)</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.H }"
                                      name="H"
                                      v-model="form.H"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  <div class="col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Evaluation /Negotiate</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.I }"
                                      name="I"
                                      v-model="form.I"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  </div>


							  <div class= "row">
							  <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Contract Endorsement</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.K }"
                                      name="K"
                                      v-model="form.K"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  <div class="col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name">Contract Award</label>
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.L }"
                                      name="L"
                                      v-model="form.L"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  </div>


							  <div class= "row">
							  <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Commencement of Contract
                                    </label>
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.M }"
                                      name="M"
                                      v-model="form.M"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  <div class="col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Contract Completion</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.N }"
                                      name="N"
                                      v-model="form.N"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div>

							  </div>

							  <div class= "row">
							  <div class = "col-md-6">

                              <div class="form-group">
                                <div class="row">
                                  <div class="col-md-4">
                                    <label for="role_name"
                                      >Contract Approval MoFEP</label
                                    >
                                  </div>

                                  <div class="col-md-8">
                                    <input
                                      type="date"
                                      class="form-control"
                                      :class="{ 'is-invalid': errors.J }"
                                      name="J"
                                      v-model="form.J"
                                      placeholder=""
                                    />
                                  </div>
                                </div>
                              </div>

							  </div></div>
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

                  </div>
                </div>
              </div>
            </div>

            <!-- Procurement Plan Details -->
            <!-- Modal -->
            <div
              class="modal fade bd-example-modal-lg"
              id="procurementPlanDetailsModal"
              role="dialog"
              aria-labelledby="myLargeModalLabel"
              aria-hidden="true"
            >
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      Procurement Plan Details Under Procurement Plan
                      {{ procurement_plan_details.financial_year_start }} -
                      {{ procurement_plan_details.financial_year_end }}
                    </h5>
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
                    <div class="container-fluid">
                      <div class="container">
                        <!-- Main content -->
                        <div class="row">
                          <div class="col-lg-12">
                            <!-- Details -->
                            <div class="card mb-4">
                              <div class="card-body">
                                <div class="mb-3 d-flex justify-content-between">
                                  <div>
                                    <span class="me-3">Created By : </span>
                                    <span class="badge badge-success"
                                      >{{ procurement_plan_details.firstName }}
                                      {{
                                        procurement_plan_details.lastName
                                      }}</span
                                    >
                                  </div>
                                  <br />
                                  <div>
                                    <span class="me-3">Created On : </span>
                                    <span class="badge badge-success">{{
                                      procurement_plan_details.created_at
                                        | customDate
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
                                        {{
                                          procurement_plan_details.amount
                                            | formatNumber
                                        }}
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
                                            procurement_plan_details.status ===
                                            'approved'
                                          "
                                          >{{
                                            procurement_plan_details.status
                                          }}</span
                                        >
                                        <span
                                          class="badge badge-warning"
                                          v-if="
                                            procurement_plan_details.status !==
                                            'approved'
                                          "
                                          >{{
                                            procurement_plan_details.status
                                          }}</span
                                        >
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <!-- /.create a User modal -->

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
  import moment from "moment";

  export default {
    props: {
      getMethods: Array,
      getCategories: Array,
      plan: Object,
      getDetails: Array,
      plan_detail: Object,
    },
    data() {
      return {
        fullPage: true,
        editMode: false,
        errors: [],
        plan_to_edit: "",
        procurement_plan_details: {},
        logged_in_user_step: "",
        isLoggedInUserApprover: false,
        form2: new Form({
          id: "",
          reason: "",
          step: "",
          user_id: "",
          status: "",
        }),
        form: new Form({
          id: "",
          brief: "",
          amount: "",
          plan_id: "",
          category_id: "",
          method_id: "",
          C: "",
          E: "",
          F: "",
          G: "",
          H: "",
          I: "",
          J: "",
          K: "",
          L: "",
          M: "",
          N: "",
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

      submitForApproval(id) {
        this.showLoader();
        axios
          .get("/submit/procurement/detail/for/approval/" + id, {
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
            window.location.reload();
          })
          .catch((error) => {
            this.hideLoader();
          });
      },

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
              window.location.reload();
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
              window.location.reload();
            })
            .catch((error) => {
              this.hideLoader();
            });
        }
      },

      ViewProcurementPlanDetails(details) {
        this.showLoader();
        this.procurement_plan_details = details;
        this.form2.step = details.step;
        this.form2.id = details.id;
        axios
          .get("/get/who/to/approve/step/" + details.step, {
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
            $("#procurementPlanDetailsModal").modal("show");
          })
          .catch((error) => {
            this.hideLoader();
          });
      },

      getFormattedDate(date) {
        return moment(date).format("YYYY");
      },

      saveProcurementPlanDetail() {
        this.$Progress.fail();
        this.showLoader();

        this.form
          .post("/create/procurement_plan/detail")
          .then((response) => {
            if (response.data.isvalid == false) {
              this.errors = response.data.errors;
              this.hideLoader();
            } else {
              Swal.fire({
                icon: "success",
                title: "Added New Plan Detail",
                text: response.data.message,
              });

              this.hideLoader();
              this.form.reset();
              window.location.href =
                "/manage/procurement_plan/details/" + this.plan.id;
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

      updateProcurementPlanDetail() {
        this.$Progress.fail();
        this.showLoader();

        this.form
          .post("/update/procurement_plan/detail")
          .then((response) => {
            if (response.data.isvalid == false) {
              this.errors = response.data.errors;
              console.log(this.errors);
            } else {
              Swal.fire({
                icon: "success",
                title: "Edited Plan Detail",
                text: response.data.message,
              });

              this.hideLoader();
              $("#createAPlan").modal("hide");
              this.form.reset();
              window.location.href =
                "/manage/procurement_plan/details/" + this.plan.id;
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

      PlanDetails(plan) {
        console.log(plan);
        window.location.href = "/manage/procurement_plan/details/" + plan.id;
      },

      //Open Modal
      openAddPlanModal() {
        this.editMode = false;
        this.form.reset();
        this.form.plan_id = this.plan.id;
        $("#createAPlan").modal("show");
      },

      //Open Modal
      EditPlan(detail) {
        this.editMode = true;
        this.form.fill(detail);
        $("#createAPlan").modal("show");
      },
    },

    created() {
      this.showLoader();
      setTimeout(() => {
        this.hideLoader();
      }, 3000);

      if(this.plan_detail != null){

        this.editMode = true;
        this.form.fill(this.plan_detail);

}

      this.form.plan_id = this.plan.id;
    },

    components: {
      UserLoggedOnNavBarComponent,
      SideBarComponent,
      Loading,
      FooterComponent,
    },
  };
  </script>
