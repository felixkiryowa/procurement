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
                      v-if="user.name === 'Procurement Officer'"
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
                          <th>Bid Details</th>
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
                          <td>
                            <button
                              class="btn btn-success"
                              @click="
                                previewevaluateBiddersWhoSubmittedBids(detail)
                              "
                            >
                              Bid Details
                            </button>
                          </td>
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
    <div
      class="modal fade bd-example-modal-lg"
      id="evaluateBiddersWhoSubmittedBids"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <form @submit.prevent="submitSelectedBidder">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Best Evaluated Bidder
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
              <div class="card">
                <div class="card-body">
                  <div class="form-group">
                    <label for="">Subject Of Procurement</label>
                    <select
                      class="form-control"
                      @change="getTenderNoticeSubmittedBids($event)"
                      :class="{ 'is-invalid': errors.plan_id }"
                      v-model="form.plan_id"
                      required
                    >
                      <option disabled value="">Select Tender Notice</option>
                      <option
                        v-for="notice in tender_notices"
                        :key="notice.id"
                        :value="notice.id"
                      >
                        {{ notice.name }}
                      </option>
                    </select>
                  </div>
                  <b-alert
                    v-if="no_submitted_bids_found"
                    show
                    variant="danger"
                    >{{ message }}</b-alert
                  >
                  <div class="text-center" v-if="show_spinner">
                    <b-spinner
                      variant="primary"
                      type="grow"
                      label="Spinning"
                    ></b-spinner>
                  </div>
                  <div v-if="form.all_provider_submitted_bids.length > 0">
                    <b>Uploaded Documents</b>
                    <hr />
                    <br />
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Select As Best Bidder</th>
                            <th>Provider</th>
                            <th>Enter Reason</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(
                              provider_submitted_bid, index
                            ) in form.all_provider_submitted_bids"
                            :key="index"
                          >
                            <td>
                              <div class="form-check">
                                <input
                                  class="form-check-input"
                                  type="radio"
                                  value="1"
                                  v-model="form.selected_bidder"
                                  @change="selectAsBestBidder($event, index)"
                                />
                              </div>
                            </td>
                            <td>
                              <span class="badge badge-primary"
                                >{{ provider_submitted_bid.firstName }}
                                {{ provider_submitted_bid.lastName }}</span
                              >
                            </td>
                            <td>
                              <div class="form-group">
                                <textarea
                                  :id="'input_' + index"
                                  class="form-control"
                                  v-model="provider_submitted_bid.reason"
                                  cols="30"
                                  rows="2"
                                  required
                                ></textarea>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <br />
                  <h4>Final Bid Price</h4>
                  <hr />
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Bid Currency</label>
                        <select
                          v-model="form.currency"
                          class="form-control"
                          required
                        >
                          <option value="">Choose Currency</option>
                          <option
                            v-for="(currency, index) in allCurrencies"
                            :key="index"
                            :value="currency.cc"
                          >
                            {{ currency.cc }} / {{ currency.name }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Amount</label>
                        <input
                          type="number"
                          placeholder="Enter Amount"
                          v-model="form.amount"
                          required
                          class="form-control"
                        />
                      </div>
                      <b class="amount-input">
                        Amount : {{ checkInputValue | formatNumber }}
                        {{ form.currency }}
                      </b>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-danger btn-sm"
                data-dismiss="modal"
              >
                Close
              </button>
              <button
                type="submit"
                :disabled="form.all_provider_submitted_bids.length === 0"
                class="btn btn-success btn-sm"
              >
                Submit
              </button>
            </div>
          </div>
        </form>
      </div>
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
    tender_notices: Array,
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
      this.editMode = false;
      this.form.reset();
      $("#evaluateBiddersWhoSubmittedBids").modal("show");
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
    