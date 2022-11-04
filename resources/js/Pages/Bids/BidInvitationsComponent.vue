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
                      v-if="user.name === 'Procurement Officer'"
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
                          <th v-if="user.name === 'Procurement Officer'">
                            Actions
                          </th>
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
                          <td>
                            <button
                              class="btn btn-success btn-sm"
                              @click="
                                viewAllSubmittedBids(
                                  detail.id,
                                  detail.reference_number
                                )
                              "
                            >
                              View Bids
                            </button>
                          </td>
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
                          <td>
                            <span class="badge badge-success">{{
                              detail.status
                            }}</span>
                          </td>
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

          <!-- Modal -->
          <div
            class="modal fade"
            id="loadSubmittedBids"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
          >
            <div class="modal-dialog modal-xl" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    Bid Invitation For Tender Notice Reference
                    {{ tender_notice_reference }}
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

                </div>
                <div class="modal-footer">
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
                                    @change="getProcurementDetails($event)"
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
                                      v-for="ds in plan_data"
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


                            <table v-if="editMode === true" class="table table-striped table-condensed">
                          <thead>
                            <tr>
                              <th>Document</th>
                              <th>Type</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                            v-for="docid in tender_docs"
                                      :key="docid.id"
                                      :value="docid.id"
                                    >

                              <td>
                                {{ docid.document_url }}
                              </td>
                              <td >
                                <span
                                  class="badge badge-danger"
                                  >{{ docid.document_type }}</span
                                >
                              </td>

                            </tr>
                          </tbody>
                        </table>
                            <br />

                        <div v-if="editMode" class="float-right">
                          <button
                            class="btn btn-success btn-sm"
                            @click="addNewAttachmentupdate($event)"
                          >
                            <i class="ti-plus"></i>
                            Add Attachment
                          </button>
                        </div>
                        <table v-if="editMode" class="table table-striped table-condensed">
                          <thead>
                            <tr>
                              <th>Add Attachment</th>
                              <th>Type</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                              v-for="(
                                uploaded_file, index
                              ) in form.updated_uploaded_files"
                              :key="index"
                            >
                              <td>
                                <div class="form-group">
                                  <label for="">Document {{ index + 1 }}</label>
                                  <input
                                    type="file"
                                    required
                                    @change="handleFileUploadupdate($event, index)"
                                    class="form-control form-control-file"
                                  />
                                </div>
                              </td>
                              <td>
                                <div class="form-group">
                                  <label for="">Select Type </label>
                                  <select
                                    class="form-control"
                                    v-model="uploaded_file.doctype"
                                    :class="{ 'is-invalid': errors.doctype }"
                                  >
                                    <option selected value="">
                                      Choose Type
                                    </option>
                                    <option value="Clarification">Clarification</option>
                                    <option value="Amendment">Amendment</option>
                                    <option value="Pre-bid Opening Minutes">Pre-bid Opening Minutes</option>
                                    <option value="Bid Opening">Bid Opening</option>
                                  </select>
                                </div>
                              </td>
                              <td v-if="index === 0">
                                <span
                                  class="badge badge-primary default-approver"
                                  >Default Attachment</span
                                >
                              </td>
                              <td v-if="index != 0">
                                <button
                                  class="btn btn-danger remove-approver btn-sm"
                                  @click="deleteRowupdate(index)"
                                >
                                  <i class="ti-trash"></i>
                                  Remove Attachment {{ index }}
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <div v-if="!editMode" class="float-right">
                          <button
                            class="btn btn-success btn-sm"
                            @click="addNewAttachment($event)"
                          >
                            <i class="ti-plus"></i>
                            Add Attachment
                          </button>
                        </div>
                        <table v-if="!editMode" class="table table-striped table-condensed">
                          <thead>
                            <tr>
                              <th>Add Attachment</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr
                              v-for="(
                                uploaded_file, index
                              ) in form.uploaded_files"
                              :key="index"
                            >
                              <td>
                                <div class="form-group">
                                  <label for="">Document {{ index + 1 }}</label>
                                  <input
                                    type="file"
                                    required
                                    @change="handleFileUpload($event, index)"
                                    class="form-control form-control-file"
                                  />
                                </div>
                              </td>
                              <td v-if="index === 0">
                                <span
                                  class="badge badge-primary default-approver"
                                  >Default Attachment</span
                                >
                              </td>
                              <td v-if="index != 0">
                                <button
                                  class="btn btn-danger remove-approver btn-sm"
                                  @click="deleteRow(index)"
                                >
                                  <i class="ti-trash"></i>
                                  Remove Attachment {{ index }}
                                </button>
                              </td>
                            </tr>
                          </tbody>
                        </table>

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
      file_count: 0,
      file_type_count: 0,
      plan_data:[],
      tender_docs:[],
      errors: [],
      submitted_bids: [],
      tender_notice_reference: "",
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
        updated_uploaded_files: [
          {
            id: 0,
            file: "",
            doctype: ""
          },
        ],
        uploaded_files: [
          {
            id: 0,
            file: "",
          },
        ],
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

    viewAllSubmittedBids(tender_notice_id, reference) {
      window.location.href = '/get/all/submitted/bids/'+btoa(tender_notice_id);
    },

    getProcurementDetails(event){
        let procurement_id = event.target.value;
        axios.get('/get/procurement/detail/'+procurement_id).then((response) => {
            this.plan_data = response.data.details;
        })
        .catch((response) => {
          this.errors = response.data;
        });
        //console.log(procurement_id)
    },

    getTenderDocuments(tender){
        axios.get('/get/tender/documents/'+tender).then((response) => {
            this.tender_docs = response.data.docs;
            console.log(this.tender_docs)
        })
        .catch((response) => {
        this.errors = response.data;
        });
        //console.log(procurement_id)
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

    addNewAttachment(event) {
      event.preventDefault();
      this.file_count = this.file_count + 1;
      this.form.uploaded_files.push({ id: this.file_count, file: "" });
    },
    deleteRow(index) {
      if (index != 0) {
        this.form.uploaded_files.splice(index, 1);
      }
    },
    handleFileUpload(event, passed_index) {
      let selected_file = event.target.files[0];
      let fileName = selected_file.name;
      let extension = fileName.substring(fileName.lastIndexOf(".") + 1);
      if (extension === "pdf" || extension === "doc") {
        let objIndex = this.form.uploaded_files.findIndex(
          (obj) => obj.id == passed_index
        );
        let reader = new FileReader();
        reader.onloadend = () => {
          this.form.uploaded_files[objIndex].file = reader.result;
        };
        reader.readAsDataURL(selected_file);
      } else {
        Swal.fire({
          icon: "error",
          title: "Inavlid Uploaded File Format",
          text: "Uploaded Attachment Should Be A PDF Or Word File",
        });
      }
    },
    addNewAttachmentupdate(event) {
      event.preventDefault();
      this.file_type_count = this.file_type_count + 1;
      this.form.updated_uploaded_files.push({ id: this.file_type_count, file: "" , doctype: ""});
    },
    deleteRowupdate(index) {
      if (index != 0) {
        this.form.updated_uploaded_files.splice(index, 1);
      }
    },


    handleFileUploadupdate(event, passed_index) {
      let selected_file = event.target.files[0];
      let fileName = selected_file.name;
      let extension = fileName.substring(fileName.lastIndexOf(".") + 1);
      if (extension === "pdf" || extension === "doc") {
        let objIndex = this.form.updated_uploaded_files.findIndex(
          (obj) => obj.id == passed_index
        );
        let reader = new FileReader();
        reader.onloadend = () => {
          this.form.updated_uploaded_files[objIndex].file = reader.result;
        };
        reader.readAsDataURL(selected_file);
      } else {
        Swal.fire({
          icon: "error",
          title: "Inavlid Uploaded File Format",
          text: "Uploaded Attachment Should Be A PDF Or Word File",
        });
      }
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
      this.getTenderDocuments(detail.id);
      this.form.fill(detail);
      this.file_type_count = 0;
      this.form.updated_uploaded_files = [
          {
            id: 0,
            file: "",
            doctype: ""
          },
        ];
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
