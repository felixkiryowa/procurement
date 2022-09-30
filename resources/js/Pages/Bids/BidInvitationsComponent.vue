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
                <div class="card-body">
                  <h4 class="card-title">
                    Invitation for Bids/Quotations List
                  </h4>
                  <p class="card-description float-lg-right">
                    <button
                      type="button"
                      class="btn btn-info btn-rounded btn-fw"
                      @click="openAddPlanModal"
                      v-if="user.user_role === 'Procurement Officer'"
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
                          <th>Subject</th>
                          <th>Procurement Plan</th>
                          <th>Bids</th>
                          <th>Procurement Type</th>
                          <th>Deadline</th>
                          <th>Display Start</th>
                          <th>Display End</th>
                          <th>Status</th>
                          <th>Date Created</th>
                          <th v-if="user.name === 'Procurement Officer'">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr :key="index" v-for="(detail, index) in bids">
                          <td>{{ index + 1 }}</td>
                          <td>{{ detail.name }}</td>
                          <td>
                            Procurement Plan
                            {{ getFormattedDate(detail.financial_year_start) }}
                            - {{ getFormattedDate(detail.financial_year_end) }}
                          </td>
                          <td><a href="#">Bids</a></td>
                          <td>{{ detail.type }}</td>
                          <td>
                            {{ new Date(detail.deadline).toLocaleString() }}
                          </td>
                          <td>
                            {{
                              new Date(
                                detail.display_start_date
                              ).toLocaleString()
                            }}
                          </td>
                          <td>
                            {{
                              new Date(detail.display_end_date).toLocaleString()
                            }}
                          </td>
                          <td><span class="badge badge-success">{{ detail.status }}</span></td>
                          <td>
                            {{ new Date(detail.created_at).toLocaleString() }}
                          </td>

                          <td v-if="user.name === 'Procurement Officer'">
                            <button
                              class="btn btn-sm btn-success"
                              @click="EditPlan(detail)"
                            >
                              Edit
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
                  editMode ? updateBidInvitation() : saveBidInvitation()
                "
                role="form"
              >
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">
                      {{
                        editMode
                          ? "Edit Bid Invitation Details"
                          : "Create Bid Invitation Details"
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
                        <div v-if="errors.exists" class="laravel-error">
                          <div v-if="errors.exists" class="error">
                            {{ errors.exists ? errors.exists : "" }}
                          </div>
                        </div>
                      </div>
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="">Procurement Plan </label>
                                </div>

                                <div class="col-md-8">
                                  <select
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.plan_id }"
                                    v-model="form.plan_id"
                                  >
                                    <option selected value="">
                                      Select Plan
                                    </option>
                                    <option
                                      v-for="plan in plans"
                                      :key="plan.id"
                                      :value="plan.id"
                                    >
                                      FY
                                      {{
                                        getFormattedDate(
                                          plan.financial_year_start
                                        )
                                      }}
                                      -
                                      {{
                                        getFormattedDate(
                                          plan.financial_year_end
                                        )
                                      }}
                                    </option>
                                  </select>
                                  <div
                                    v-if="errors.plan_id"
                                    class="invalid-feedback"
                                  >
                                    <div v-if="errors.plan_id" class="error">
                                      {{
                                        errors.plan_id ? errors.plan_id[0] : ""
                                      }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="">Subject of Procurement </label>
                                </div>

                                <div class="col-md-8">
                                  <select
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.subject_id }"
                                    v-model="form.subject_id"
                                  >
                                    <option selected value="">
                                      Select Subject
                                    </option>
                                    <option
                                      v-for="ds in plan_details"
                                      :key="ds.id"
                                      :value="ds.id"
                                    >
                                      {{ ds.brief }}
                                    </option>
                                  </select>
                                  <div
                                    v-if="errors.subject_id"
                                    class="invalid-feedback"
                                  >
                                    <div v-if="errors.subject_id" class="error">
                                      {{
                                        errors.subject_id
                                          ? errors.subject_id[0]
                                          : ""
                                      }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="role_name"
                                    >Reference Number</label
                                  >
                                </div>

                                <div class="col-md-8">
                                  <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                      'is-invalid': errors.reference_number,
                                    }"
                                    name="reference_number"
                                    v-model="form.reference_number"
                                    placeholder="Enter Reference Number"
                                  />

                                  <div
                                    v-if="errors.reference_number"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      v-if="errors.reference_number"
                                      class="error"
                                    >
                                      {{
                                        errors.reference_number
                                          ? errors.reference_number[0]
                                          : ""
                                      }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="email">Type of Procurement</label>
                                </div>

                                <div class="col-md-8">
                                  <select
                                    class="form-control"
                                    v-model="form.type"
                                    :class="{ 'is-invalid': errors.type }"
                                  >
                                    <option selected value="">
                                      Choose Type
                                    </option>
                                    <option value="works">Works</option>
                                    <option value="services">Services</option>
                                    <option value="goods">Goods</option>
                                  </select>
                                  <div
                                    v-if="errors.type"
                                    class="invalid-feedback"
                                  >
                                    <div v-if="errors.type" class="error">
                                      {{ errors.type ? errors.type[0] : "" }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="role_name">Summary</label>
                                </div>

                                <div class="col-md-8">
                                  <textarea
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.details }"
                                    name="details"
                                    v-model="form.details"
                                    placeholder="Enter Brief"
                                  ></textarea>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="role_name"
                                    >Submission Deadline</label
                                  >
                                </div>

                                <div class="col-md-8">
                                  <input
                                    type="date"
                                    class="form-control"
                                    :class="{ 'is-invalid': errors.deadline }"
                                    name="deadline"
                                    v-model="form.deadline"
                                    placeholder=""
                                  />
                                  <div
                                    v-if="errors.deadline"
                                    class="invalid-feedback"
                                  >
                                    <div v-if="errors.deadline" class="error">
                                      {{
                                        errors.deadline
                                          ? errors.deadline[0]
                                          : ""
                                      }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="role_name"
                                    >Display Period From</label
                                  >
                                </div>

                                <div class="col-md-8">
                                  <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                      'is-invalid': errors.display_start_date,
                                    }"
                                    name="display_start_date"
                                    v-model="form.display_start_date"
                                    placeholder=""
                                  />
                                  <div
                                    v-if="errors.display_start_date"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      v-if="errors.display_start_date"
                                      class="error"
                                    >
                                      {{
                                        errors.display_start_date
                                          ? errors.display_start_date[0]
                                          : ""
                                      }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="role_name"
                                    >Display Period To</label
                                  >
                                </div>

                                <div class="col-md-8">
                                  <input
                                    type="date"
                                    class="form-control"
                                    :class="{
                                      'is-invalid': errors.display_end_date,
                                    }"
                                    name="display_end_date"
                                    v-model="form.display_end_date"
                                    placeholder=""
                                  />
                                  <div
                                    v-if="errors.display_end_date"
                                    class="invalid-feedback"
                                  >
                                    <div
                                      v-if="errors.display_end_date"
                                      class="error"
                                    >
                                      {{
                                        errors.display_end_date
                                          ? errors.display_end_date[0]
                                          : ""
                                      }}
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-group">
                              <div class="row">
                                <div class="col-md-4">
                                  <label for="email">Status</label>
                                </div>
                                <div class="col-md-8">
                                  <select
                                    class="form-control"
                                    v-model="form.status"
                                    :class="{ 'is-invalid': errors.status }"
                                  >
                                    <option selected value="">
                                      Choose Status
                                    </option>
                                    <option value="saved">Saved</option>
                                    <option value="published">Published</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="extended">Extended</option>
                                  </select>
                                  <div
                                    v-if="errors.status"
                                    class="invalid-feedback"
                                  >
                                    <div v-if="errors.status" class="error">
                                      {{
                                        errors.status ? errors.status[0] : ""
                                      }}
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
import moment from "moment";

export default {
  props: {
    getMethods: Array,
    getCategories: Array,
    plan: Object,
    getDetails: Array,
    plans: Array,
    plan_details: Array,
    bids: Array,
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      errors: [],
      form: new Form({
        id: "",
        plan_id: "",
        subject_id: "",
        reference_number: "",
        details: "",
        deadline: "",
        type: "",
        status: "",
        display_end_date: "",
        display_start_date: "",
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

    getFormattedDate(date) {
      return moment(date).format("YYYY");
    },

    saveBidInvitation() {
      this.$Progress.fail();
      this.showLoader();

      this.form
        .post("/create/bid/invitation")
        .then((response) => {
          if (response.data.isvalid == false) {
            this.errors = response.data.errors;
            console.log(this.errors);
          } else {
            Swal.fire({
              icon: "success",
              title: "Added New Bid Invitation",
              text: response.data.message,
            });

            this.hideLoader();
            $("#createAPlan").modal("hide");
            this.form.reset();
            window.location.href = "/manage/bid/invitations/";
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

    updateBidInvitation() {
      this.$Progress.fail();
      this.showLoader();

      this.form
        .post("/update/bid/invitation")
        .then((response) => {
          if (response.data.isvalid == false) {
            this.errors = response.data.errors;
            console.log(this.errors);
          } else {
            Swal.fire({
              icon: "success",
              title: "Edited Bid  Invitation",
              text: response.data.message,
            });

            this.hideLoader();
            $("#createAPlan").modal("hide");
            this.form.reset();
            window.location.href = "/manage/bid/invitations/";
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

    //Open Modal
    openAddPlanModal() {
      this.editMode = false;
      this.form.reset();
      $("#createAPlan").modal("show");
    },

    //Open Modal
    EditPlan(detail) {
      this.editMode = true;
      console.log(detail.id);
      this.form.fill(detail);
      $("#createAPlan").modal("show");
    },
  },

  created() {
    setTimeout(() => {
      this.hideLoader();
    }, 3000);
  },

  components: {
    UserLoggedOnNavBarComponent,
    SideBarComponent,
    Loading,
    FooterComponent,
  },
};
</script>
