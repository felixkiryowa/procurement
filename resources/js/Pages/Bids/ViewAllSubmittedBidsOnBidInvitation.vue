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
                    Bid Invitation For Tender Notice With Reference
                    {{ tender_notice_reference }}
                  </h4>
                  <div class="table-responsive">
                    <table id="AllSubmittedBids" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Amount</th>
                          <th>Brief description</th>
                          <th>Bid Validity Period</th>
                          <th>Submitted By</th>
                          <th>Status</th>
                          <th>Date Created</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          :key="index"
                          v-for="(submitted_bid, index) in submitted_bids"
                        >
                          <td>{{ index + 1 }}</td>
                          <td>
                            {{ submitted_bid.amount | formatNumber }}
                            {{ submitted_bid.currency }}
                          </td>
                          <td>{{ submitted_bid.brief_description }}</td>
                          <td>
                            {{ submitted_bid.start_date | customDate }} -
                            {{ submitted_bid.end_date | customDate }}
                          </td>
                          <td>
                            <span class="badge badge-success"
                              >{{ submitted_bid.firstName }}
                              {{ submitted_bid.lastName }}</span
                            >
                          </td>
                          <td>
                            <span class="badge badge-primary">{{
                              submitted_bid.status
                            }}</span>
                          </td>
                          <td>
                            {{ submitted_bid.created_at | customDate }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
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
import moment from "moment";

export default {
  props: {
    submitted_bids: Array,
    tender_notice_reference: String,
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      file_count: 0,
      file_type_count: 0,
      plan_data: [],
      tender_docs: [],
      errors: [],
    //   submitted_bids: [],
    //   tender_notice_reference: "",
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
            doctype: "",
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
    $("#AllSubmittedBids").DataTable();
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

    getProcurementDetails(event) {
      let procurement_id = event.target.value;
      axios
        .get("/get/procurement/detail/" + procurement_id)
        .then((response) => {
          this.plan_data = response.data.details;
        })
        .catch((response) => {
          this.errors = response.data;
        });
      //console.log(procurement_id)
    },

    getTenderDocuments(tender) {
      axios
        .get("/get/tender/documents/" + tender)
        .then((response) => {
          this.tender_docs = response.data.docs;
          console.log(this.tender_docs);
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
      this.form.updated_uploaded_files.push({
        id: this.file_type_count,
        file: "",
        doctype: "",
      });
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
          doctype: "",
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
  