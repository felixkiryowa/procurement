<template>
  <div class="container-scroller">
    <UserLoggedOnNavBarComponent :app="app" :user="user" />
    <div class="container-fluid page-body-wrapper">
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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">
                    Welcome {{ user.firstName }} {{ user.lastName }}
                  </h3>
                </div>

                <div class="col-lg-12 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="modal-title" id="exampleModalLabel">
                        Procurement Plan
                        {{ getFormattedDate(bid_details.financial_year_start) }}
                        -
                        {{ getFormattedDate(bid_details.financial_year_end) }}

                        / Bid Reference {{ bid_details.reference_number }}
                      </h5>
                    </div>
                    <div class="card-body">
                      <h4>BID NAME : {{ bid_details.name }}</h4>
                      <hr />
                      <div class="mb-3 d-flex justify-content-between">
                        <div>
                          <span class="me-3">Created By : </span>
                          <span class="badge badge-success">{{
                            bid_details.organisationName
                          }}</span>
                        </div>
                        <br />
                        <div>
                          <span class="me-3">Created On : </span>
                          <span class="badge badge-success">{{
                            bid_details.created_at | customDate
                          }}</span>
                        </div>
                      </div>
                      <div class="mb-3 d-flex justify-content-between">
                        <div>
                          <span class="me-3">Display Start Date : </span>
                          <span class="badge badge-success">{{
                            bid_details.display_start_date | customDate
                          }}</span>
                        </div>
                        <br />
                        <div>
                          <span class="me-3">Display End Date : </span>
                          <span class="badge badge-success">{{
                            bid_details.display_end_date | customDate
                          }}</span>
                        </div>
                      </div>
                      <div class="mb-3 d-flex justify-content-between">
                        <div>
                          <span class="me-3">Brief Description : </span>
                          <span class="badge badge-success">{{
                            bid_details.details
                          }}</span>
                        </div>
                      </div>
                      <table class="table table-striped">
                        <tbody>
                          <tr>
                            <td colspan="2">Amount</td>
                            <td class="text-end">
                              {{ bid_details.budget_amount | formatNumber }}
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2">Bid Category</td>
                            <td class="text-end">
                              <span class="badge badge-danger">{{
                                bid_details.category_name
                              }}</span>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2">Method</td>
                            <td class="text-danger text-end">
                              <span class="badge badge-danger">{{
                                bid_details.method_name
                              }}</span>
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
          <h1 hidden>{{ checkIfUserIsIdle }}</h1>
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
    bid_details: Object,
  },
  //   mixins: [ValidationHelper],
  data() {
    return {
      fullPage: true,
      bid_details: {},
    };
  },
  mounted() {
    
  },
  computed: {
    ...mapState("registration", ["isLoading"]),
    //   ...mapGetters("registration", ["allCountries"]),

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
  },

  methods: {
    ...mapActions("registration", ["showLoader", "hideLoader"]),

    getFormattedDate(date) {
      return moment(date).format("YYYY");
    },

    previewBidDetails(detail) {
      this.bid_details = detail;
      $("#tenderNoticeDetailsModal").modal("show");
    },

    submitBid(id, reference_number) {
      this.showLoader();
      axios
        .get("/check/user/submitted/bid/already/" + id, {
          headers: {
            "X-Frame-Options": "sameorigin",
            "X-Content-Type-Options": "nosniff",
            "strict-transport-security": "max-age=31536000",
          },
        })
        .then((response) => {
          if (response.data.success) {
            window.location.href = "/submit/bid/" + btoa(id);
            this.hideLoader();
          } else {
            Swal.fire({
              icon: "error",
              title: "Submit Bid Response",
              text:
                "Already Submitted Bid For Tender Notice With Reference " +
                reference_number,
            });
            this.hideLoader();
          }
        })
        .catch((error) => {});
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