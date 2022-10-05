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
                  <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">
                          Manage Submitted Bids
                        </h4>
                        <div class="table-responsive pt-3">
                          <table
                            id="allPlans"
                            class="table table-bordered table-sm table-striped"
                          >
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Company</th>
                                <th>Notice Name</th>
                                <th>Reference Number</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr :key="index" v-for="(detail, index) in submitted_bids">
                                <td>{{ index + 1 }}</td>
                                <td>{{ detail.organisationName }}</td>
                                <td>{{ detail.name }}</td>
                                <td>{{ detail.reference_number }}</td>
                                <td>{{ detail.amount  | formatNumber }}</td>
                                <td>{{ detail.currency }}</td>
                                <td>
                                  <span class="badge badge-success">{{
                                    detail.status
                                  }}</span>
                                </td>
                                <td>{{ detail.created_at | customDate }}</td>
                                <td>
                                  <button
                                    @click="previewBidDetails(detail)"
                                    class="btn btn-sm btn-success"
                                  >
                                    View Details
                                  </button>
                                  
                                  <button v-if="detail.status === 'saved'" @click="editSubmittedBid(detail.id)" class="btn btn-sm btn-success">
                                     Edit Submitted Bid
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
  
      <!-- Modal -->
      <div
        class="modal fade bd-example-modal-lg"
        id="tenderNoticeDetailsModal"
        role="dialog"
        aria-labelledby="myLargeModalLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Procurement Plan
                {{ getFormattedDate(bid_details.financial_year_start) }}
                -
                {{ getFormattedDate(bid_details.financial_year_end) }}
  
  
                / Bid Reference {{ bid_details.reference_number }}
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
                          <h4>BID NAME : {{ bid_details.name }}</h4>
                          <hr>
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
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-danger btn-sm"
                data-dismiss="modal"
              >
                Close
              </button>
            </div>
          </div>
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
        submitted_bids: Array,
    },
    //   mixins: [ValidationHelper],
    data() {
      return {
        fullPage: true,
        bid_details: {},
      };
    },
    mounted() {
      $("#allPlans").DataTable();
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

      editSubmittedBid(id) {
        window.location.href = '/edit/submitted/bid/'+btoa(id);
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