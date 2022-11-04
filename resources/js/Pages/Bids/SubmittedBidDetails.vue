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
                  <h5 class="modal-title" id="exampleModalLabel">
                    {{ choosen_detail.title }} : {{ choosen_detail.period }}
                  </h5>
                </div>
                <div class="card-body">
                  <div class="mb-3 d-flex justify-content-between">
                    <div>
                      <span class="me-3">Provider : </span>
                      <span class="badge badge-success">{{
                        choosen_detail.organisationName
                      }}</span>
                    </div>
                    <br />
                    <div>
                      <span class="me-3">Validity Start Date : </span>
                      <span class="badge badge-success">{{
                        choosen_detail.start_date | customDate
                      }}</span>
                    </div>
                  </div>
                  <div class="mb-3 d-flex justify-content-between">
                    <div>
                      <span class="me-3">Validity End Date : </span>
                      <span class="badge badge-success">{{
                        choosen_detail.end_date | customDate
                      }}</span>
                    </div>
                  </div>
                  <div class="mb-3 d-flex justify-content-between">
                    <div>
                      <span class="me-3">Brief Description : </span>
                      <span class="badge badge-success">{{
                        choosen_detail.brief_description
                      }}</span>
                    </div>
                  </div>
                  <div class="mb-3 d-flex justify-content-between">
                    <div>
                      <span class="me-3">Amount : </span>
                      <span class="badge badge-success"
                        >{{ choosen_detail.amount | formatNumber }}
                        {{ choosen_detail.currency }}</span
                      >
                    </div>
                  </div>
                  <div v-if="form.submitted_docs_array.length > 0">
                    <b>Uploaded Documents</b>
                    <hr />
                    <br />
                    <div class="table-responsive">
                      <table class="table table-striped table-condensed">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Uploaded Attachment</th>
                            <th>Tracking Number</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(
                              uploaded_file, index
                            ) in form.submitted_docs_array"
                            :key="index"
                          >
                            <td>{{ index + 1 }}</td>
                            <td>{{ uploaded_file.file_name }}</td>
                            <td>
                              <div class="form-group">
                                <input
                                  type="number"
                                  class="form-control"
                                  placeholder="Enter Tracking Number"
                                  :id="'input_' + index"
                                  v-model="uploaded_file.tracking_number"
                                />
                                <br />
                                <span
                                  class="text-danger hide-span"
                                  :id="'error_input_' + index"
                                >
                                  Document Tracking number is required
                                </span>
                              </div>
                            </td>
                            <td>
                              <button
                                @click="
                                  downloadUploadedFile(
                                    index,
                                    uploaded_file.document_id,
                                    uploaded_file.file_name
                                  )
                                "
                                class="btn btn-success btn-sm"
                              >
                                Download
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
          </div>

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
    choosen_detail: Object,
    submitted_documents: Array,
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      tracking_number_error: false,
      errors: [],
      submitted_bids: [],
      choosen_detail: {},
      file_count: 0,
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
        submitted_docs_array: [],
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

    downloadUploadedFile(passed_index, document_id, file_name) {
      let objIndex = this.form.submitted_docs_array.findIndex(
        (obj) => obj.id == passed_index
      );

      let selected_document = this.form.submitted_docs_array[objIndex];

      selected_document.tracking_number === ""
        ? (this.tracking_number_error = true)
        : (this.tracking_number_error = false);

      if (!this.tracking_number_error) {
        const requestData = new FormData();
        requestData.append(
          "tracking_number",
          selected_document.tracking_number
        );
        requestData.append("document_id", document_id);
        axios
          .post("/download/uploaded/file", requestData, {
            headers: {
              "X-Frame-Options": "sameorigin",
              "X-Content-Type-Options": "nosniff",
              "strict-transport-security": "max-age=31536000",
            },
          })
          .then((response) => {
            if (response.data.success) {
              var fileLink = document.createElement("a");
              fileLink.href = "/" + response.data.document_path;
              fileLink.setAttribute("download", file_name);
              document.body.appendChild(fileLink);
              fileLink.click();
            } else {
              Swal.fire({
                icon: "error",
                title: "DownLoad Submitted Bid Documents",
                text: response.data.message,
              });
            }
          })
          .catch((error) => {});
      } else {
        $("#input_" + passed_index).attr(
          "style",
          "border: 1px solid red !important"
        );
        $("#error_input_" + passed_index).attr(
          "style",
          "display:block !important"
        );
      }
    },

    previewSubmittedBidDocumentDetails(submitted_documents) {
      if (submitted_documents.length > 0) {
        let documents = submitted_documents;
        this.file_count = this.file_count + 1;

        for (let index = 0; index < documents.length; index++) {
          this.form.submitted_docs_array.push({
            id: index,
            file_name: documents[index].document,
            tracking_number: "",
            document_id: documents[index].id,
          });
        }
      }
    },

    getFormattedDate(date) {
      return moment(date).format("YYYY");
    },

    viewAllSubmittedBids(tender_notice_id, reference) {
      this.showLoader();
      axios
        .get("/get/all/submitted/bids/" + tender_notice_id, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          if (response.data.length > 0) {
            this.tender_notice_reference = reference;
            this.submitted_bids = response.data;
            $("#loadSubmittedBids").modal("show");
          }
          this.hideLoader();
        })
        .catch((error) => {
          this.hideLoader();
        });
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
  },

  created() {
    this.previewSubmittedBidDocumentDetails(this.submitted_documents);
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
    