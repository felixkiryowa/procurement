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
                  <h4 class="card-title">Bidders Evaluated Bidders</h4>
                  <p class="card-description float-lg-right">
                    <button
                      class="btn btn-info btn-rounded btn-fw"
                      @click="openModalToAddBidderEvaluation"
                      v-if="user.name === 'Procurement Officer' || user.name === 'Company'"
                    >
                      Evaluate Bidders
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
                          <th>Provider</th>
                          <th>Plan Name</th>
                          <th>Invitationfor Bids / Quotations</th>
                          <th>Bid Amount</th>
                          <th>Valid From</th>
                          <th>Valid To</th>
                          <th>Created On</th>
                          <th>Updated On</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          :key="index"
                          v-for="(detail, index) in best_evaluated_bidders"
                        >
                          <td>{{ index + 1 }}</td>
                          <td>{{ detail.provider }}</td>
                          <td>Procurement Plan {{ detail.period }}</td>
                          <td>{{ detail.title }}</td>
                          <td>
                            {{ detail.amount | formatNumber }}
                            {{ detail.currency }}
                          </td>
                          <td>
                            {{ detail.start_date }}
                          </td>
                          <td>{{ detail.end_date }}</td>
                          <td>
                            {{ detail.created_at | customDate }}
                          </td>
                          <td>
                            {{ detail.updated_at | customDate }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
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
    best_evaluated_bidders: Array,
  },
  data() {
    return {
      fullPage: true,
      editMode: false,
      tracking_number_error: false,
      errors: [],
      submitted_bids: [],
      tender_notice_submitted_bids: [],
      no_submitted_bids_found: false,
      choosen_detail: {},
      file_count: 0,
      tender_notice_reference: "",
      show_spinner: false,
      message: "",
      form: new Form({
        id: "",
        plan_id: "",
        all_provider_submitted_bids: [],
        selected_bidder: "",
        amount: "",
        currency: "",
      }),
      submitted: false,
    };
  },

  mounted() {
    $("#allPlans").DataTable();
  },
  computed: {
    ...mapState("registration", ["isLoading"]),
    ...mapGetters("registration", ["allCurrencies"]),

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

    checkInputValue() {
      return this.form.amount === "" ? 0 : this.form.amount;
    },
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),
    submitSelectedBidder() {
      this.showLoader();

      if (this.form.selected_bidder === "") {
        this.hideLoader();
        Swal.fire({
          icon: "info",
          title: "Selecting Best Bidder",
          text: "Please Select A Best Bidder, By Selecting Radio Button",
        });
      } else {
        this.form
          .post("/submit/best/evaluated/bidder", {
            headers: {
              "X-Frame-Options": "sameorigin",
              "X-Content-Type-Options": "nosniff",
              "strict-transport-security": "max-age=31536000",
            },
          })
          .then((response) => {
            this.hideLoader();
            Swal.fire({
              icon: "success",
              title: "Added New Bid Invitation",
              text: response.data.message,
            });
            this.hideLoader();
            window.location.reload();
          })
          .catch((error) => {
            this.hideLoader();
          });
      }
    },

    getTenderNoticeSubmittedBids(event) {
      this.show_spinner = true;
      let tender_notice_id = event.target.value;
      axios
        .get("/get/tender/notice/submitted/bids/" + tender_notice_id, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          if (response.data.length > 0) {
            this.show_spinner = false;
            this.tender_notice_submitted_bids = response.data;
            for (
              let index = 0;
              index < this.tender_notice_submitted_bids.length;
              index++
            ) {
              this.form.all_provider_submitted_bids.push({
                id: index,
                choosen_bid: 0,
                tender_notice_id:
                  this.tender_notice_submitted_bids[index].tender_notice_id,
                submitted_bid_id: this.tender_notice_submitted_bids[index].id,
                lastName: this.tender_notice_submitted_bids[index].lastName,
                firstName: this.tender_notice_submitted_bids[index].firstName,
                reason: "",
              });
            }
            this.no_submitted_bids_found = false;
            this.show_spinner = false;
          } else {
            this.no_submitted_bids_found = true;
            this.show_spinner = false;
          }
        })
        .catch((error) => {});
    },

    selectAsBestBidder(event, passed_index) {
      let selected_value = event.target.value;
      let objIndex = this.form.all_provider_submitted_bids.findIndex(
        (obj) => obj.id == passed_index
      );

      this.form.all_provider_submitted_bids[objIndex].choosen_bid = 1;
    },

    previewevaluateBiddersWhoSubmittedBids(detail) {
      this.choosen_detail = detail;
      this.showLoader();
      axios
        .get("/get/submitted/bids/document/" + detail.id, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          if (response.data.length > 0) {
            let documents = response.data;

            for (let index = 0; index < documents.length; index++) {
              this.form.all_provider_submitted_bids.push({
                id: index,
                file_name: documents[index].document,
                tracking_number: "",
                document_id: documents[index].id,
              });
            }
          }
          this.hideLoader();
          $("#evaluateBiddersWhoSubmittedBids").modal("show");
        })
        .catch((error) => {
          this.hideLoader();
        });
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
            $("#createBidEvaluation").modal("hide");
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
            $("#createBidEvaluation").modal("hide");
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
    openModalToAddBidderEvaluation() {
      window.location.href = '/submit/best/evaluated/bids';
    },

    //Open Modal
    EditPlan(detail) {
      this.editMode = true;
      console.log(detail.id);
      this.form.fill(detail);
      $("#createBidEvaluation").modal("show");
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
    